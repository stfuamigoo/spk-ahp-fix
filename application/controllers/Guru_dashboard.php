<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Guru_dashboard extends CI_Controller
{
   public function index()
   {
      if (!$this->session->userdata('loggedIn')) {
         redirect('user_auth');
      } else {
         if ($this->session->userdata('user_role') == 3) {
            redirect('home');
         }

         $this->load->model('User_model', 'User');
         $this->load->model('Siswa_model', 'Siswa');
         $this->load->model('Nilai_Siswa_model', 'Nilai_Siswa');
         $data['title'] = "Dashboard";

         // get all user information from the database
         $username = $this->session->userdata('username');
         $data['user_data'] = $this->User->getUserByUsername($username);
         $siswa = $this->Siswa->getAllSiswa();
         $nilai_siswa = $this->Nilai_Siswa->getAllNilaiSiswa();
         $user = $this->User->getAllUser();
         $data['total_siswa'] = $this->Siswa->countSiswa($siswa);
         $data['total_user'] = $this->User->countUser($user);
         $data['total_nilai_siswa'] = $this->Nilai_Siswa->countNilaiSiswa($nilai_siswa);


         $this->load->view('templates/admin_headbar', $data);
         $this->load->view('templates/guru_sidebar');
         $this->load->view('templates/admin_topbar');
         $this->load->view('guru_dashboard/index');
         $this->load->view('templates/admin_footer');
      }
   }
}
