<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Validation\Factory as Validator;
use Illuminate\Support\Facades\Hash;

/**
 * Command aimed to create admin user from the console
 * Class CreateAdmin
 * @package App\Console\Commands
 */
class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin for the application';

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
    public function handle(User $users,Validator $validator )
    {
        $email = $this->anticipate('Admin Email:',[],'app@moviestore.com');
        $name = $this->anticipate('Admin Name:',[],'Admin');
        $password = $this->secret("Admin Password:");
        $password_confirmation = $this->secret("Confirm Password:");

        $input = $validator->make(compact('email','name','password','password_confirmation'), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

         if($input->fails()){
            $this->error($input->errors()->toJson());

            return 1;
         }

        $user = $users->create([
             'name' => $name,
             'password' => Hash::make($password),
             'email' => $email
         ]);

         $user->attachRole('admin');

         $this->info("User $email created.");
    }
}
