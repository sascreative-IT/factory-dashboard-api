<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ResetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:user-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The command is to reset a user account';

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
     * @return int
     */
    public function handle()
    {
        $userRole = null;
        $email = $this->ask('What is the email address ?');
        $userPassword = $this->_generatePassword();
        $user = User::where('email', $email)->first();

        if ($user) {

            $user->update([
                'password' => $userPassword
            ]);

            $this->info("\n");
            $this->info("The user account has been created successfully");
            $this->info("The password is : $userPassword");
            $this->info("The department is : $user->department");
            $this->info("\n");
        } else {
            $name = $this->ask('What is the name of the user ?');
            $userPassword = $this->_generatePassword();
            $department = $this->choice(
                "Choose a department ?",
                ['CS', 'Factory', 'WH', 'Shop','Administrator'],
                0,
                $maxAttempts = null,
                $allowMultipleSelections = false
            );
            $user = User::create(
                [
                    'name' => $name,
                    'email' => $email,
                    'email_verified_at' => now(),
                    'department' => $department,
                    'password' => $userPassword,
                    'remember_token' => Str::random(10),
                ]
            );
            if ($user) {
                $this->info("\n");
                $this->info("The user account has been created successfully");
                $this->info("The password is : $userPassword");
                $this->info("\n");
            }
        }
        return 0;
    }

    private function _generatePassword($length = 12)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' .
            '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
