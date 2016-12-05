 <?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class TaskPageTest extends TestCase
{
    use DatabaseTransactions;

    public function setup()
    {
        parent::setup();
        App::setLocale('en');

        $this->createUser();
        $this->createClient();
        $this->createRole();
        $this->createClientPermission();
        $this->createLeadPermission();
        $this->createDepartment();

	}
    /**
     * Test the case where description and title is.
     */
    public function testTaskCase()
    {
        $this->createTask('open');

        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->visit('tasks/' . $this->task->id)
            ->seePageIs('tasks/' . $this->task->id)
            ->seeInElement('.taskcase', $this->task->title)
            ->seeInElement('.taskcase', $this->task->description);     	
    }

    public function testSidebarTaskInformation()
    {
        $this->createTask('open');

        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->visit('tasks/' . $this->task->id)
            ->seePageIs('tasks/' . $this->task->id)
            ->see('TASK INFORMATION')
            ->seeInElement('.sidebarbox',  $this->task->assignee->name)
            ->seeInElement('.sidebarbox', 'Created: ' . date('d F, Y, H:i', strtotime($this->task->created_at)))
            ->seeInElement('.sidebarbox', date('d, F Y', strtotime($this->task->deadline)))
            ->seeInElement('.sidebarbox', 'status: Open')
            ->see('Assign new user')
            ->see('Close task')
            ->see('TIME MANAGMENT')
            ->see('Title')
            ->see('Time')
            ->see('Add time')
            ->see('Create Invoice'); 
    }

    public function testSidebarDoesntSeeAssignIfTaskIsClosed()
    {
        $this->createTask('closed');

        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->visit('tasks/' . $this->task->id)
            ->see('TASK INFORMATION')
            ->seeInElement('.sidebarbox',  $this->task->assignee->name)
            ->seeInElement('.sidebarbox', 'Created: ' . date('d F, Y, H:i', strtotime($this->task->created_at)))
            ->seeInElement('.sidebarbox', date('d, F Y', strtotime($this->task->deadline)))
            ->seeInElement('.sidebarbox', 'Status: Closed')
            ->dontSee('Assign new user')
            ->dontSee('Close task')
            ->see('TIME MANAGMENT')
            ->see('Title')
            ->see('Time')
            ->see('Add time')
            ->see('Create Invoice'); 
    }
}