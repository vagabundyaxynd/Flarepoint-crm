 <?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\PermissionRole;
use App\Models\Client;

class UpdateClientTest extends TestCase
{
    use DatabaseTransactions;

    protected $client;
    protected $role;
    protected $faker;

    public function setup()
    {
        parent::setup();
        App::setLocale('en');

        $this->createUser();
        $this->createClient();
        $this->createRole();
    }

    public function testCanNotAccessUpdatePageWithOutPermission()
    {
        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->see('Clients')
            ->click('All Clients')
            ->dontSee('Edit')
            ->visit('clients/' . $this->client->id . '/edit')
            ->see('Not allowed to update client');
    }

    public function testCanUpdateClient()
    {
        $this->updateClientPermission();

         $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->see('Clients')
            ->click('All Clients')
            ->see('Edit')
            ->visit('clients/' . $this->client->id . '/edit')
            ->type($this->faker->name, 'name')
            ->type($this->faker->email, 'email')
            ->type($this->faker->address, 'address')
            ->press('Update client');

        //Assert that the informtion has actully updated
        $this->assertNotEquals($this->client->name, $this->client->fresh()->name);
        $this->assertNotEquals($this->client->address, $this->client->fresh()->address);
        $this->assertNotEquals($this->client->email, $this->client->fresh()->email);
    }



}