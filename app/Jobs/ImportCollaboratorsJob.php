<?php

namespace App\Jobs;

use App\Mail\CollaboratorImportResult;
use App\Models\Collaborator;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;

class ImportCollaboratorsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userId, string $filePath)
    {
        $this->userId = $userId;
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::find($this->userId);

        if (!$user) {
            Log::error('User not found: ' . $this->userId);
            return;
        }

        $fullPath = storage_path('app/private/' . $this->filePath);

        if (!file_exists($fullPath)) {
            Log::error('File not found: ' . $fullPath);
            return;
        }

        $csv = Reader::createFromPath($fullPath, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();

        $imported = 0;
        $skipped = [];

        foreach ($records as $index => $record) {
            $record = array_map('trim', $record);

            $exists = Collaborator::where('email', $record['email'])
                ->orWhere('cpf', $record['cpf'])
                ->exists();

            if ($exists) {
                $skipped[] = [
                    'name' => $record['name'],
                    'email' => $record['email'],
                    'cpf' => $record['cpf'],
                    'reason' => 'Duplicated email or CPF',
                ];

                continue;
            }

            try {
                Collaborator::create([
                    'name' => $record['name'],
                    'email' => $record['email'],
                    'cpf' => $record['cpf'],
                    'city' => $record['city'],
                    'state' => $record['state'],
                    'manager_id' => $user->id,
                ]);

                $imported++;
            } catch (\Exception $e) {
                $skipped[] = [
                    'name' => $record['name'],
                    'email' => $record['email'],
                    'cpf' => $record['cpf'],
                    'reason' => 'Error: ' . $e->getMessage(),
                ];
            }
        }

        Mail::to($user->email)->queue(
            new CollaboratorImportResult($imported, $skipped)
        );

        Storage::disk('local')->delete($this->filePath);
    }
}
