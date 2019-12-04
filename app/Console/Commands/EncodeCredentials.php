<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class EncodeCredentials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nest:credentials {--save}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set nest credentials';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->warn('Note: this hides the email and password, but they are easily accessible.  This is not secure password storage.');
        $email = $this->ask('Enter Nest Email:');
        $password = $this->ask('Enter Nest Password:');
        $email_env = "NEST_USERNAME=" . encrypt($email);
        $password_env = "NEST_PASSWORD=" . encrypt($password);

        if ($this->option('save')) {
            $env_file = base_path('.env');
            $env = file_get_contents($env_file);
            $output = '';
            foreach (explode(PHP_EOL, $env) as $line) {
                if (!Str::startsWith($line, ['NEST_USERNAME', 'NEST_PASSWORD'])) {
                    $output .= $line . PHP_EOL;
                }
            }

            $output .= $email_env . PHP_EOL;
            $output .= $password_env . PHP_EOL;

            file_put_contents($env_file, $output);

            $this->info('Variables saved to .env');
            return 0;
        }


        $this->info('.env variables');
        $this->line($email_env);
        $this->line($password_env);
        return 0;
    }
}
