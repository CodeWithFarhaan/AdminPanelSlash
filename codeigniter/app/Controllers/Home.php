<?php

namespace App\Controllers;

use App\Models\AuditLogModel;
use App\Models\UserModel;
use App\Models\CampaignModel;
use App\Models\AccessLevelModel;
use App\Models\ChatModel;

class Home extends BaseController
{
    protected $session;

    // Constructor to initialize session
    public function __construct()
    {
        // Initialize session service
        $this->session = \Config\Services::session();
    }

    public function index(): string
    {
        return view('login');
    }

    //---------------------------------------users-------------------------------------------------------------
    public function users()
    {
        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }
        $user_model = new UserModel();
        $users = $user_model->findAll();
        $loggedinUser = $this->session->get('user');
        $loggedinUserName = $loggedinUser->name;
        $role = $loggedinUser->userRole;
        return view('/users', ['users' => $users, 'role' => $role, 'loggedinUser' => $loggedinUser]);
    }

    //---------------------------------------adduser(+)--------------------------------------------------------
    public function adduser()
    {
        // Check if the logged-in user is authenticated
        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }

        // Get the logged-in user details
        $loggedinUser = $this->session->get('user');
        $loggedinUserName = $loggedinUser->name;
        $role = $loggedinUser->userRole;

        // Check if the logged-in user is an admin
        if ($role !== 'admin') {
            return redirect()->to('/login');  // or return an error if non-admin users cannot add users
        }

        if (isset($_POST['name'])) {
            $user_model = new UserModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT)
            ];
            $result = $user_model->save($data);

            // If the user is added successfully, create an audit log
            if ($result) {
                // Create an audit log entry
                $auditlog_model = new AuditLogModel(); // Assuming you have a model for AuditLog
                $auditlog_data = [
                    'datetime' => date('Y-m-d H:i:s'),
                    'action' => 'create', // Action is 'create' for adding a new user
                    'user_id' => $loggedinUser->id,  // ID of the logged-in admin
                    'name' => $loggedinUserName,  // Name of the logged-in admin
                    'logs' => 'Created user: ' . $data['name']
                ];
                $auditlog_model->save($auditlog_data);  // Save the log to the auditlog table

                // Redirect with a success message
                return redirect()->to('/users')->with('success', 'User added successfully!');
            } else {
                // If user creation failed, return with an error message
                return redirect()->back()->with('error', 'Failed to add user');
            }
        }

        return view('adduser');
    }




    //----------------------------------------------updateuser--------------------------------------------------
    public function updateUser()
    {
        $user_model = new UserModel();
        // Check if the user exists
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $userRole = $this->request->getPost('userRole');
        $updatedData = [];

        // Get logged-in user details
        $loggedinUser = $this->session->get('user');
        $loggedinUserName = $loggedinUser->name;
        $role = $loggedinUser->userRole;

        // Only admin can update userRole
        if ($role === 'admin' && $userRole) {
            $updatedData['userRole'] = $userRole;
        }

        if ($name) {
            $updatedData['name'] = $name;
        }
        if ($email) {
            $updatedData['email'] = $email;
        }

        // Update the user
        $result = $user_model->update($id, $updatedData);

        // Audit log model
        $auditlog_model = new AuditLogModel();

        // Prepare the base data for audit log
        $auditData = [
            'datetime' => date('Y-m-d H:i:s'), // Current timestamp
            'action' => 'update', // Action type (update)
            'user_id' => $loggedinUser->id, // ID of the logged-in user
            'name' => $loggedinUserName, // Name of the logged-in admin user
            'logs' => 'User with ID ' . $id . ' was updated.' // Initial log entry
        ];

        // If the update is successful, add more specific details to the audit log
        if ($result) {
            // Add detailed changes for the updated fields
            $details = [];

            if ($name) {
                $details[] = 'Name changed to ' . $name . '.';
            }
            if ($email) {
                $details[] = 'Email changed to ' . $email . '.';
            }
            if ($userRole && $role === 'admin') {
                $details[] = 'User role changed to ' . $userRole . '.';
            }

            // Join all details into a single log message
            $auditData['logs'] = implode(' ', $details);

            // Save the audit log
            $auditlog_model->save($auditData);

            // Redirect with success message
            return redirect()->to("/users")->with("success", "User updated successfully");
        } else {
            // If update fails, save a failed audit log entry
            $auditData['logs'] = 'Failed to update user with ID ' . $id . '.';
            $auditlog_model->save($auditData);

            // Redirect with error message
            return redirect()->to("/users")->with("error", "Failed to update user");
        }
    }


    //---------------------------------------deleteuser--------------------------------------------------------
    public function deleteUser($id)
    {
        $user_model = new UserModel();

        // Check if the user exists
        $user = $user_model->find($id); // Or however you check for the user existence
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found');
        }

        // Get logged-in user details
        $loggedinUser = $this->session->get('user');
        $loggedinUserName = $loggedinUser->name;
        $role = $loggedinUser->userRole;

        // Only admin can delete a user
        if ($role !== 'admin') {
            return redirect()->to('/users')->with('error', 'You do not have permission to delete users.');
        }

        // Audit log model
        $auditlog_model = new AuditLogModel();

        // Prepare the base data for the audit log
        $auditData = [
            'datetime' => date('Y-m-d H:i:s'), // Current timestamp
            'action' => 'delete', // Action type (delete)
            'user_id' => $loggedinUser->id, // ID of the logged-in user
            'name' => $loggedinUserName, // Name of the logged-in admin user
            'logs' => 'User with ID ' . $id . ' was deleted.' // Initial log entry
        ];

        // Delete the user from the database
        $result = $user_model->delete($id);

        // If the delete is successful, save the audit log
        if ($result) {
            // Save the audit log entry with success message
            $auditlog_model->save($auditData);

            // Redirect with success message
            return redirect()->to('/users')->with('success', 'User deleted successfully');
        } else {
            // If delete fails, log the failure in the audit log
            $auditData['logs'] = 'Failed to delete user with ID ' . $id . '.'; // Log failure details
            $auditlog_model->save($auditData);

            // Redirect with error message
            return redirect()->to('/users')->with('error', 'Failed to delete user');
        }
    }


    //-------------------------------------------------signup--------------------------------------------------------------
    public function signup()
    {
        if ($this->session->has('user')) {
            return redirect()->to('/users');
        }
        if (isset($_POST['name'])) {
            $user_model = new UserModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT)
            ];
            $result = $user_model->save($data);
            if ($result) {
                return redirect()->to('/login')->with('success', 'Registration successful! Please log in.');
            } else {
                return redirect()->back()->with('error', 'Failed to register. Please try again.');
            }
        }
        return view('signup');
    }

    //----------------------------------------------login---------------------------------------------------------------
    public function login()
    {
        if ($this->session->has('user')) {
            return redirect()->to('/users');
        }
        if (isset($_POST['email'])) {
            $user_model = new UserModel();
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $user = $user_model->where('email', $email)->first();
            if ($user) {
                if (password_verify($password, $user->password)) {
                    $this->session->set("user", $user);
                    return redirect()->to('/users')->with('success', 'Login successful!');
                } else {
                    return redirect()->back()->with('error', 'Invalid password. Please try again.');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid email. Please try again.');
            }
        }
        return view('login');
    }
    //----------------------------------------------logout---------------------------------------------------------------
    public function logout()
    {
        $this->session->remove('user');
        $this->session->setFlashdata('success', 'You have logged out successfully.');
        return redirect()->to('/login');
    }

    //---------------------------------------campaigns-----------------------------------------------------------
    public function campaigns()
    {
        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }
        $campaign_model = new CampaignModel();
        $campaigns = $campaign_model->findAll();
        $loggedinCampaign = $this->session->get('user');
        $loggedinUserName = $loggedinCampaign->name;
        $role = $loggedinCampaign->userRole;
        return view('/campaigns', ['campaigns' => $campaigns, 'role' => $role, 'loggedinCampaign' => $loggedinCampaign]);
    }

    //---------------------------------------addcampaign(+)--------------------------------------------------------   
    public function addcampaign()
    {
        // Check if the user is authenticated
        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }

        // Get the logged-in user details
        $loggedinUser = $this->session->get('user');
        $loggedinUserName = $loggedinUser->name;
        $role = $loggedinUser->userRole;

        // Only proceed if the user is an admin (optional based on your business rules)
        if ($role !== 'admin') {
            return redirect()->to('/campaigns')->with('error', 'You do not have permission to add a campaign.');
        }

        // Proceed with adding the campaign
        if (isset($_POST['name'])) {
            $campaign_model = new CampaignModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'client' => $this->request->getPost('client'),
            ];

            // Save the campaign data
            $result = $campaign_model->save($data);

            // Create an audit log if the campaign was added successfully
            if ($result) {
                // Audit log model
                $auditlog_model = new AuditLogModel();

                // Prepare the data for the audit log
                $auditData = [
                    'datetime' => date('Y-m-d H:i:s'), // Current timestamp
                    'action' => 'create', // Action type (create)
                    'user_id' => $loggedinUser->id, // ID of the logged-in admin user
                    'name' => $loggedinUserName, // Name of the logged-in admin user
                    'logs' => 'Created Campaign: ' . $data['name']
                ];

                // Save the audit log
                $auditlog_model->save($auditData);

                // Redirect with a success message
                return redirect()->to('/campaigns')->with('success', 'Campaign added successfully!');
            } else {
                // If campaign creation failed, return with an error message
                return redirect()->back()->with('error', 'Failed to add campaign');
            }
        }

        return view('addcampaign');
    }



    //----------------------------------------------updatecampaign----------------------------------------------------------------
    public function updatecampaign()
    {
        $campaign_model = new CampaignModel();

        // Check if the campaign exists
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $client = $this->request->getPost('client');
        $updatedData = [];

        // Get logged-in user details
        $loggedinUser = $this->session->get('user');
        $loggedinUserName = $loggedinUser->name;
        $loggedinUserRole = $loggedinUser->userRole;

        // Prepare the data for the campaign update
        if ($name) {
            $updatedData['name'] = $name;
        }
        if ($description) {
            $updatedData['description'] = $description;
        }
        if ($client) {
            $updatedData['client'] = $client;
        }

        // Update the campaign in the database
        $result = $campaign_model->update($id, $updatedData);

        // Audit log model
        $auditlog_model = new AuditLogModel();

        // Prepare audit log data
        $auditData = [
            'datetime' => date('Y-m-d H:i:s'), // Current timestamp
            'action' => 'update', // Action type (update)
            'user_id' => $loggedinUser->id, // ID of the logged-in user who performed the action
            'name' => $loggedinUserName, // Name of the logged-in user who performed the action
            'logs' => 'Campaign with ID ' . $id . ' was updated.' // General details about the update
        ];

        // If the update is successful, add more specific details to the audit log
        if ($result) {
            $auditData['details'] = ''; // Initialize details string

            // Add specific changes to the details
            if ($name) {
                $auditData['details'] .= ' Name changed to ' . $name . '.';
            }
            if ($description) {
                $auditData['details'] .= ' Description changed to ' . $description . '.';
            }
            if ($client) {
                $auditData['details'] .= ' Client changed to ' . $client . '.';
            }

            // Save the audit log
            $auditlog_model->save($auditData);

            // Redirect with success message
            return redirect()->to("/campaigns")->with("success", "Campaign updated successfully");
        } else {
            // If the update fails, save a failed audit log entry
            $auditData['details'] = 'Failed to update campaign with ID ' . $id . '.'; // Log failure details
            $auditlog_model->save($auditData);

            // Redirect with error message
            return redirect()->to("/campaigns")->with("error", "Failed to update campaign");
        }
    }


    //---------------------------------------deletecampaign--------------------------------------------------------
    public function deleteCampaign($id)
    {
        $campaign_model = new CampaignModel();
        // Check if campaign exists
        $campaign = $campaign_model->find($id); // Or however you check for the campaign existence
        if (!$campaign) {
            return redirect()->to('/campaigns')->with('error', 'campaign not found');
        }

        // Get logged-in user details
        $loggedinUser = $this->session->get('user');
        $loggedinUserName = $loggedinUser->name;
        $role = $loggedinUser->userRole;

        // Only admin can delete a campaign
        if ($role !== 'admin') {
            return redirect()->to('/campaigns')->with('error', 'You do not have permission to delete campaign.');
        }

        // Audit log model
        $auditlog_model = new AuditLogModel();

        // Prepare data for audit log
        $auditData = [
            'datetime' => date('Y-m-d H:i:s'), // Current timestamp
            'action' => 'delete', // Action type (delete)
            'user_id' => $loggedinUser->id, // ID of the logged-in user
            'name' => $loggedinUserName, // ID of the logged-in admin user
            'logs' => 'Campaign with ID ' . $id . ' was deleted.' // Details of the action
        ];

        // Delete the user from the database
        $result = $campaign_model->delete($id);

        // If the delete is successful, save the audit log
        if ($result) {
            // Save the audit log entry
            $auditlog_model->save($auditData);

            // Redirect with success message
            return redirect()->to('/campaigns')->with('success', 'Campaign deleted successfully');
        } else {
            // If delete fails, log the failure in the audit log
            $auditData['logs'] = 'Failed to delete campaign with ID ' . $id . '.'; // Log failure details
            $auditlog_model->save($auditData);

            // Redirect with error message
            return redirect()->to('/campaigns')->with('error', 'Failed to delete campaign');
        }
    }

    //---------------------------------------chats-------------------------------------------------------------
    public function chats()
    {
        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }
        $user_model = new UserModel();
        $users = $user_model->findAll();
        $loggedinUser = $this->session->get('user');
        $role = $loggedinUser->userRole;
        $usersCount = count($users);  // Count how many users are in the array
        return view('/chats', ['users' => $users, 'usersCount' => $usersCount, 'role' => $role, 'loggedinUser' => $loggedinUser]);
    }

    //---------------------------------------accesslevel-------------------------------------------------------------
    public function accessLevel()
    {
        $user_model = new UserModel();
        $users = $user_model->findAll();
        $loggedinUser = $this->session->get('user');
        $loggedinUserName = $loggedinUser->name;
        $role = $loggedinUser->userRole;
        return view('accesslevel', ['users' => $users, 'role' => $role, 'loggedinUser' => $loggedinUser]);
    }

    //---------------------------------------update accesslevel-------------------------------------------------------
    public function updateRole($id)
    {
        $newRole = $this->request->getPost('roles');
        $loggedinUser = $this->session->get('user');

        // Check if the logged-in user has 'admin' role
        if ($loggedinUser->userRole !== 'admin') {
            return redirect()->to('/accesslevel')->with('error', 'You do not have permission to change roles.');
        }

        // Find the user by ID
        $user_model = new UserModel();
        $user = $user_model->find($id);

        if ($user) {
            // Store the old role before updating
            $oldRole = $user->userRole;

            // Update the user's role
            $user->userRole = $newRole;
            $user_model->save($user);

            // Log the action in the auditlog table
            $this->logAudit($loggedinUser->id, 'update', "Changed role for user {$user->name} from {$oldRole} to {$newRole}");
        }

        // Redirect after role update
        return redirect()->to('/accesslevel');
    }

    protected function logAudit($userId, $action, $description)
    {
        // Prepare the audit log data
        $auditData = [
            'datetime' => date('Y-m-d H:i:s'),  // Current date and time
            'action' => $action,
            'user_id' => $userId,                // ID of the user performing the action
            'name' => 'Role Update',             // You can customize this name
            'logs' => $description,              // Description of the action
        ];

        // Insert the audit log into the database
        $auditModel = new AuditLogModel();
        $auditModel->insert($auditData);
    }


    //---------------------------------------auditlog-------------------------------------------------------------
    public function auditlog()
    {
        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }
        $auditlog_model = new AuditLogModel();
        $loggedinUser = $this->session->get('user');
        $role = $loggedinUser->userRole;
        $auditlogs = $auditlog_model->findAll();
        return view('/auditlog', ['auditlogs' => $auditlogs, 'role' => $role, 'loggedinUser' => $loggedinUser]);
    }


    //---------------------------------------deleteauditlog (optional)--------------------------------------------------------
    // public function deleteauditlog($id)
    // {
    //     $auditlog_model = new AuditLogModel();
    //     $auditlog = $auditlog_model->find($id);
    //     if (!$auditlog) {
    //         return redirect()->to('/auditlog')->with('error', 'Auditlog not found.');
    //     }
    //     $result = $auditlog_model->delete($id);
    //     if ($result) {
    //         return redirect()->to('/auditlog')->with('success', 'Auditlog deleted successfully.');
    //     } else {
    //         return redirect()->to('/auditlog')->with('error', 'Failed to delete auditlog.');
    //     }
    // }
}
