<?php

namespace App\Controllers;

use App\Models\CampaignModel;
use App\Models\ChatModel;
use App\Models\UserModel;

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
        return view('welcome_message');
    }

    //---------------------------------------users-------------------------------------------------------------
    public function users()
    {
        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }
        $user_model = new UserModel();
        $users = $user_model->findAll();
        return view('/users', ['users' => $users]);
    }

    //---------------------------------------adduser(+)--------------------------------------------------------
    public function adduser()
    {
        if (isset($_POST['name'])) {
            $user_model = new UserModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT)
            ];
            $result = $user_model->save($data);
            if ($result) {
                return redirect()->to('/users')->with('success', 'user added successful!');
            } else {
                return redirect()->back()->with('error', 'Failed to add user');
            }
        }
        return view('adduser');
    }

    //----------------------------------------------updateuser--------------------------------------------------
    public function updateUser()
    {
        $user_model = new UserModel();
        // Check if user exists
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $userRole = $this->request->getPost('userRole');
        $updatedData = [];

        if ($name)
            $updatedData['name'] = $name;
        if ($email)
            $updatedData['email'] = $email;
        if ($userRole)
            $updatedData['userRole'] = $userRole;

        $result = $user_model->update($id, $updatedData);
        if (is_object($result) && method_exists($result, 'getStatusCode') && $result->getStatusCode() == 200) {
            return redirect()->to("/users")->with("success", "User updated successfully");
        } else {
            return redirect()->to("/users")->with("error", "Failed to update user");
        }
    }

    //---------------------------------------deleteuser--------------------------------------------------------
    public function deleteUser($id)
    {
        $user_model = new UserModel();

        // Check if user exists
        $user = $user_model->find($id); // Or however you check for the user existence
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found');
        }

        // Delete the user from the database
        $result = $user_model->delete($id);

        // Check if delete operation was successful
        if ($result) {
            return redirect()->to('/users')->with('success', 'User deleted successfully');
        } else {
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

            // Find the user by email
            $user = $user_model->where('email', $email)->first();

            if ($user) {
                // Check if the password is correct
                if (password_verify($password, $user->password)) {
                    // Check if the user is an admin
                    if ($user->userRole === 'admin') {
                        // Set the session if the user is an admin
                        $this->session->set("user", $user);
                        return redirect()->to('/users')->with('success', 'Login successful!');
                    } else {
                        // If the user is not an admin, deny login
                        return redirect()->back()->with('error', "Your'e not an admin sorry...");
                    }
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
        return view('/campaigns', ['campaigns' => $campaigns]);
    }

    //---------------------------------------addcampaign(+)--------------------------------------------------------   
    public function addcampaign()
    {
        if (isset($_POST['name'])) {
            $campaign_model = new CampaignModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'client' => $this->request->getPost('client'),
            ];
            $result = $campaign_model->save($data);
            if ($result) {
                return redirect()->to('/campaigns')->with('success', 'campaign added successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to add campaign');
            }
        }
        return view('addcampaign');
    }

    //----------------------------------------------updatecampaign----------------------------------------------------------------
    public function updatecampaign()
    {
        $campaign_model = new CampaignModel();
        // Check if user exists
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $client = $this->request->getPost('client');
        $updatedData = [];

        if ($name)
            $updatedData['name'] = $name;
        if ($description)
            $updatedData['description'] = $description;
        if ($client)
            $updatedData['client'] = $client;

        $result = $campaign_model->update($id, $updatedData);
        if (is_object($result) && method_exists($result, 'getStatusCode') && $result->getStatusCode() == 200) {
            return redirect()->to("/campaigns")->with("success", "Campaign updated successfully");
        } else {
            return redirect()->to("/campaigns")->with("error", "Failed to update campaign");
        }
    }


    //---------------------------------------deletecampaign--------------------------------------------------------
    public function deleteCampaign($id)
    {
        $campaign_model = new CampaignModel();
        // Check if user exists
        $campaign = $campaign_model->find($id); // Or however you check for the user existence
        if (!$campaign) {
            return redirect()->to('/campaigns')->with('error', 'campaign not found');
        }

        // Delete the user from the database
        $result = $campaign_model->delete($id);

        // Check if delete operation was successful
        if ($result) {
            return redirect()->to('/campaigns')->with('success', 'Campaign deleted successfully');
        } else {
            return redirect()->to('/campaigns')->with('error', 'Failed to delete campaign');
        }
    }

    //---------------------------------------chats-------------------------------------------------------------
    public function chats()
    {
        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }
        $chat_model = new ChatModel();
        $chats = $chat_model->findAll();
        return view('/chats', ['chats' => $chats]);
    }
}