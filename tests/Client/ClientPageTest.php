  <?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\PermissionRole;
use App\Models\Client;
use App\Models\Department;

class ClientPageTest extends TestCase
{
    use DatabaseTransactions;

    protected $client;
    protected $role;
    protected $faker;
    protected $user;
    protected $department;

    public function setup()
    {
        parent::setup();

        $this->faker = \Faker\Factory::create();

        //Create a user for loggin in without permissions
        $this->user = new User;
        $this->user->name = 'Casper';
        $this->user->email = 'bottelet@flarepoint.com';
        $this->user->address = $this->faker->address;
        $this->user->password = bcrypt('admin');
        $this->user->save();

        //Create test client
        $this->client = New Client;
        $this->client->name = $this->faker->name;
        $this->client->company_name = $this->faker->company('name');
        $this->client->email = $this->faker->email;
        $this->client->industry_id = $this->faker->numberBetween($min = 1, $max = 25);
        $this->client->fk_user_id = $this->user->id;
        $this->client->save();
        //Create Role
        $this->role = new Role;
        $this->role->display_name = 'Test role';
        $this->role->name = 'Test Role';
        $this->role->description = 'Role for testing';
        $this->role->save();

        $newrole = new RoleUser;
        $newrole->role_id = $this->role->id;
        $newrole->user_id = $this->user->id;
        $newrole->timestamps = false;
        $newrole->save();

        $createTask = new PermissionRole;
        $createTask->role_id = $this->role->id;
        $createTask->permission_id = '7';
        $createTask->timestamps = false;
        $createTask->save();

        $createLead = new PermissionRole;
        $createLead->role_id = $this->role->id;
        $createLead->permission_id = '9';
        $createLead->timestamps = false;
        $createLead->save();

        $this->department = new Department;
        $this->department->name = 'Test Department';
        $this->department->save();

        \DB::table('department_user')->insert([
            'department_id' => $this->department->id,
            'user_id' => $this->user->id
        ]);


	}
    public function testCanSeeTabs()
    {
        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->visit('clients/' . $this->client->id)
            //tabs
            ->seeInElement('.nav-tabs', 'Tasks')
            	->seeInElement('#task', 'All tasks')
	            ->seeInElement('#task', 'Add new task')
	            ->seeInElement('#task', 'Title')
	            ->seeInElement('#task', 'Assigned user')
	            ->seeInElement('#task', 'Created at')
	            ->seeInElement('#task', 'Deadline')
            ->seeInElement('.nav-tabs', 'Leads')
            	->seeInElement('#lead', 'All leads')
	            ->seeInElement('#lead', 'Add new lead')
	            ->seeInElement('#lead', 'Title')
	            ->seeInElement('#lead', 'Assigned user')
	            ->seeInElement('#lead', 'Created at')
	            ->seeInElement('#lead', 'Deadline')
            ->seeInElement('.nav-tabs', 'Documents')
            	->seeInElement('#document', 'All documents')
	            ->seeInElement('#document', 'File')
	            ->seeInElement('#document', 'Size')
	            ->seeInElement('#document', 'Created at')
            ->seeInElement('.nav-tabs', 'Invoices')
	            ->seeInElement('#invoice', 'ID')
	            ->seeInElement('#invoice', 'Hours')
	            ->seeInElement('#invoice', 'Total amount')
	            ->seeInElement('#invoice', 'Invoice sent')
	            ->seeInElement('#invoice', 'Payment received');     	
    }



}