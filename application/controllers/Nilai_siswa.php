<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nilai_siswa extends CI_Controller
{

   public function __construct()
   {
      parent::__construct();
      $this->load->library('form_validation');
      $this->load->model('User_model', 'User');
      $this->load->model('Nilai_Siswa_model', 'Nilai_Siswa');
      $this->load->model('Siswa_model', 'Siswa');
   }

   public function index()
   {
      if (!$this->session->userdata('loggedIn')) {
         redirect('user_auth');
      } else {
         if ($this->session->userdata('user_role') == 3) {
            redirect('home');
         }

         // get all user information from the database
         $username = $this->session->userdata('username');
         $nama_siswa = $this->session->userdata('nama_siswa');
         $data['user_data'] = $this->User->getUserByUsername($username);
         $data['data_siswa'] = $this->Siswa->getSiswaByID($nama_siswa);
         $data['nilaisiswa'] = $this->Nilai_Siswa->getAllNilaiSiswa();
         $data['title'] = "Manage Nilai Siswa";

         $this->load->view('templates/admin_headbar', $data);
         $this->load->view('templates/admin_sidebar');
         $this->load->view('templates/admin_topbar');
         $this->load->view('nilai_siswa/index');
         $this->load->view('templates/admin_footer');
      }
   }

   public function tambah_nilai_siswa()
   {
      # code...
      if (!$this->session->userdata('loggedIn')) {
         redirect('user_auth');
      } else {
         if ($this->session->userdata('user_role') == 3) {
            redirect('home');
         }

         // get all user information from the database
         $username = $this->session->userdata('username');
         $data['user_data'] = $this->User->getUserByUsername($username);

         $this->form_validation->set_rules('akademik', 'Nilai Akademik', 'required|trim', ['required' => 'Nilai Akademik harus diisi']);
         $this->form_validation->set_rules('sikap', 'Nilai Sikap', 'required|trim', ['required' => 'Nilai Sikap harus diisi']);
         $this->form_validation->set_rules('keaktifan', 'Nilai Keaktifan', 'required|trim', ['required' => 'Nilai Keaktifan harus diisi']);

         if ($this->form_validation->run() == false) {
            $data['title'] = "Admin|Tambah Nilai Siswa";
            $this->load->view('templates/admin_headbar', $data);
            $this->load->view('templates/admin_sidebar');
            $this->load->view('templates/admin_topbar');
            $this->load->view('nilai_siswa/tambah_nilai_siswa');
            $this->load->view('templates/admin_footer');
         } else {
            $data = [
               'akademik' => $this->input->post('akademik'),
               'sikap' => $this->input->post('sikap'),
               'keaktifan' => $this->input->post('keaktifan'),
               'id_nilai_siswa' => $this->input->post('id_nilai_siswa')
            ];

            $this->Nilai_Siswa->insert($data);

            $this->session->set_flashdata('success_alert', 'Siswa berhasil ditambah!');
            redirect('nilai_siswa');
         }
      }
   }

   public function hapus_siswa($id)
   {
      # code...
      $this->Siswa->deleteSiswa($id);
      $this->session->set_flashdata('success_alert', 'Siswa berhasil dihapus!');
      redirect('siswa');
   }

   public function edit_siswa($id_siswa)
   {
      # code...
      if (!$this->session->userdata('loggedIn')) {
         redirect('user_auth');
      } else {
         if ($this->session->userdata('user_role') == 3) {
            redirect('home');
         }

         // get all user information from the database
         $username = $this->session->userdata('username');
         $nama_siswa = $this->session->userdata('nama_siswa');
         $data['siswa']  = $this->Siswa->getSiswaByNmSiswa($nama_siswa);
         $data['user_data'] = $this->User->getUserByUsername($username);

         $this->form_validation->set_rules('nama_siswa', 'Nama Siswa', 'required|trim', ['required' => 'Nama Siswa harus diisi']);
         $this->form_validation->set_rules('nisn', 'NISN', 'required|trim', ['required' => 'NISN harus diisi']);
         $this->form_validation->set_rules('nis', 'NIS', 'required|trim', ['required' => 'NIS harus diisi']);
         $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required|trim', ['required' => 'Jenis Kelamin harus diisi']);
         $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim', ['required' => 'Alamat harus diisi']);
         $this->form_validation->set_rules('no_telepon', 'Nomor Telp', 'required|trim', ['required' => 'Nomor Telp harus diisi']);

         if ($this->form_validation->run() == false) {
            $data['title'] = "Admin|Edit Siswa";
            $data['alt'] = $this->Siswa->getSiswaByID($id_siswa);
            $this->load->view('templates/admin_headbar', $data);
            $this->load->view('templates/admin_sidebar');
            $this->load->view('templates/admin_topbar');
            $this->load->view('siswa/edit_siswa');
            $this->load->view('templates/admin_footer');
         } else {
            $data = [
               'nama_siswa' => $this->input->post('nama_siswa'),
               'nisn' => $this->input->post('nisn'),
               'nis' => $this->input->post('nis'),
               'jenis_kelamin' => $this->input->post('jenis_kelamin'),
               'alamat' => $this->input->post('alamat'),
               'no_telepon' => $this->input->post('no_telepon'),
               'id_siswa' => $id_siswa
            ];

            $this->Siswa->editSiswaData($data);

            $this->session->set_flashdata('success_alert', 'Siswa berhasil diedit!');
            redirect('siswa');
         }
      }
   }

   public function accessBlocked()
   {
      $this->load->view('auth/blocked');
   }
}