<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Artikel extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_artikel');
    }


    public function index()
    {
        $data = array(
            'title' => 'Artikel',
            'artikel' => $this->m_artikel->get_data(),

            'isi' => 'beranda/v_artikel',

        );

        $this->load->view('layout/v_wraper_backend', $data, FALSE);
    }

    public function add()
    {
        $this->form_validation->set_rules(
            'judul_artikel',
            'Judul Artikel',
            'required',
            array('required' => '%s Harus Diisi !!!')
        );
        $this->form_validation->set_rules(
            'teks_artikel',
            'Teks Artikel',
            'required',
            array('required' => '%s Harus Diisi !!!')
        );


        if ($this->form_validation->run() == TRUE) {
            $config['upload_path'] = './assets/gambar_artikel/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|ico|jfif';
            $config['max_size']     = '10000';
            $this->upload->initialize($config);
            $field_name = "gambar";
            if (!$this->upload->do_upload($field_name)) {
                $data = array(
                    'title' => 'Add artikel',
                    // 'kategori' => $this->m_kategori->get_all_data(),
                    'error_upload' => $this->upload->display_errors(),
                    'isi' => 'beranda/v_add_artikel'
                );

                $this->load->view('layout/v_wraper_backend', $data, FALSE);
            } else {
                $upload_data = array('uploads' => $this->upload->data());
                $config['image_library'] = 'gd2';
                $config['source_image'] = './assets/gambar_artikel/' . $upload_data['uploads']['file_name'];
                $this->load->library('image_lib', $config);

                $data = array(
                    'judul_artikel' => $this->input->post('judul_artikel'),
                    'teks_artikel' => $this->input->post('teks_artikel'),

                    'gambar' => $upload_data['uploads']['file_name'],
                );
                $this->m_artikel->add($data);
                $this->session->set_flashdata('pesan', 'Data berhasil ditambahkan');
                redirect('artikel');
            }
        }
        $data = array(
            'title' => 'Add artikel',
            // 'kategori' => $this->m_kategori->get_all_data(),
            'isi' => 'beranda/v_add_artikel'
        );

        $this->load->view('layout/v_wraper_backend', $data, FALSE);
    }

    public function edit($id_artikel = NULL)
    {
        $this->form_validation->set_rules(
            'judul_artikel',
            'Judul Artikel',
            'required',
            array('required' => '%s Harus Diisi !!!')
        );
        $this->form_validation->set_rules(
            'teks_artikel',
            'Teks Artikel',
            'required',
            array('required' => '%s Harus Diisi !!!')
        );

        if ($this->form_validation->run() == TRUE) {
            $config['upload_path'] = './assets/gambar_artikel/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|ico|jfif';
            $config['max_size']     = '2000';
            $this->upload->initialize($config);
            $field_name = "gambar";
            if (!$this->upload->do_upload($field_name)) {
                $data = array(
                    'title' => 'Edit Barang',

                    'artikel' => $this->m_artikel->get_data($id_artikel),
                    'error_upload' => $this->upload->display_errors(),
                    'isi' => 'beranda/v_edit'
                );

                $this->load->view('layout/v_wraper_backend', $data, FALSE);
            } else {
                //menghapus gambar tersimpan
                $artikel = $this->m_artikel->get_data($id_artikel);
                if ($artikel->gambar != "") {
                    unlink('./assets/gambar_artikel/' . $artikel->gambar);
                }
                //end hapus gambar
                $upload_data = array('uploads' => $this->upload->data());
                $config['image_library'] = 'gd2';
                $config['source_image'] = './assets/gambar/' . $upload_data['uploads']['file_name'];
                $this->load->library('image_lib', $config);

                $data = array(
                    'id_artikel' => $id_artikel,
                    'judul_artikel' => $this->input->post('judul_artikel'),
                    'teks_artikel' => $this->input->post('teks_artikel'),
                    'gambar' => $upload_data['uploads']['file_name'],
                );
                $this->m_artikel->edit($data);
                $this->session->set_flashdata('pesan', 'Data berhasil diedit');
                redirect('barang');
            } //jika tanpa ganti gambar
            $data = array(
                'id_artikel' => $id_artikel,
                'judul_artikel' => $this->input->post('judul_artikel'),
                'teks_artikel' => $this->input->post('teks_artikel'),

            );
            $this->m_artikel-->edit($data);
            $this->session->set_flashdata('pesan', 'Data berhasil diedit');
            redirect('artikel');
        }
        $data = array(
            'title' => 'Edit Artikel',
            'artikel' => $this->m_artikel->get_data($id_artikel),
            'isi' => 'beranda/v_edit'
        );

        $this->load->view('layout/v_wraper_backend', $data, FALSE);
    }
}
        
    /* End of file  artikel.php */
