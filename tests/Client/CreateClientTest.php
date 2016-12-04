 <?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\PermissionRole;

class CreateClientTest extends TestCase
{
    use DatabaseTransactions;

    public function testCanNotAccessCreatePageWithOutPermission()
    {
        //Create a user for loggin in without permissions
        $user = new User;
        $user->name = 'Casper';
        $user->email = 'bottelet@flarepoint.com';
        $user->password = bcrypt('admin');
        $user->save();

        $role = new Role;
        $role->display_name = 'Test role';
        $role->name = 'Test Role';
        $role->description = 'Role for testing';
        $role->save();
 
        $newrole = new RoleUser;
        $newrole->role_id = $role->id;
        $newrole->user_id = $user->id;
        $newrole->timestamps = false;
        $newrole->save();

        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->see('Clients')
            ->dontSee('New Client')
            ->visit('/clients/create')
            ->see('Not allowed to create client!')
            ->seePageIs('/clients');
    }

    public function testCanCreateClientWithPermission()
    {
         $faker = \Faker\Factory::create();
        //Create a user for loggin in without permissions
        $user = new User;
        $user->name = 'Casper';
        $user->email = 'bottelet@flarepoint.com';
        $user->password = bcrypt('admin');
        $user->save();

        $role = new Role;
        $role->display_name = 'Test role';
        $role->name = 'Test Role';
        $role->description = 'Role for testing';
        $role->save();
 
        $newRole = new RoleUser;
        $newRole->role_id = $role->id;
        $newRole->user_id = $user->id;
        $newRole->timestamps = false;
        $newRole->save();

        $createClient = new PermissionRole;
        $createClient->role_id = $role->id;
        $createClient->permission_id = '4';
        $createClient->timestamps = false;
        $createClient->save();

        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->see('Clients')
            ->click('New Client')
            ->seePageIs('/clients/create')
            ->type($faker->name, 'name')
            ->type($faker->email, 'email')
            ->type($faker->address, 'address')
            ->type($faker->randomNumber(8), 'vat')
            ->type($faker->company('name'), 'company_name')
            ->type($faker->postcode(), 'zipcode')
            ->type($faker->city(), 'city')
            ->type($faker->randomNumber(8), 'primary_number')
            ->type($faker->randomNumber(8), 'secondary_number')
            ->type($faker->company('suffix'), 'company_type')
            ->select($faker->numberBetween($min = 1, $max = 25), 'industry_id')
            ->select(1, 'fk_user_id')
            ->press('Create New Client')
            ->see('Client successfully added')
            ->seePageIs('/clients');

    }
}