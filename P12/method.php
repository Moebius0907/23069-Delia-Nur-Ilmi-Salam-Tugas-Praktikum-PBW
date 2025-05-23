<?php
require_once "koneksi.php";

class Book
{
    //Mengambil semua buku yang ada di database 
    public function get_books()
    {
        global $koneksi;
        $query = "SELECT * FROM books";
        $data = array();
        $result = $koneksi->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $row->price = (float) $row->price;
            $data[] = $row;
        }

        //Ketika berhasil mengambil data buku, API respon
        $response = array(
            'status' => 200,
            'message' => 'Success',
            'data' => $data
        );

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    //Mengambil buku berdasarkan yang ada di database 
    public function get_book($id = 0)
    {
        global $koneksi;
        $query = "SELECT * FROM books";
        if ($id != 0) {
            $query .= " WHERE id=" . $id . " LIMIT 1";
        }
        $data = array();
        $result = $koneksi->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $row->price = (float) $row->price;
            $data[] = $row;
        }

        $response = array(
            'status' => 200,
            'message' => 'Success',
            'data' => $data
        );

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    //Fungsi untuk insert buku ke database 
    public function insert_book()
    {
        global $koneksi;
        //Mengecek inputan 
        $arrcheckpost = array(
            'name' => '',
            'price' => '',
            'qty' => '',
            'author' => '',
            'publisher' => ''
        );

        $hitung = count(array_intersect_key($_POST, $arrcheckpost));

        //Validasi data 
        if ($hitung == count($arrcheckpost)) {
            $result = mysqli_query($koneksi, "INSERT INTO books SET name = '$_POST[name]', price = '$_POST[price]', qty = '$_POST[qty]', author = '$_POST[author]', publisher = '$_POST[publisher]'");
            if ($result) {
                $response = array(
                    'status' => 200,
                    'message' => 'Success'
                );
            } else { //Jika data tidak sesuai
                $response = array(
                    'status' => 500,
                    'message' => 'Internal server error.'
                );
            }
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Bad Request',
                'error' => "Form data did not match!"
            );
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function delete_book($id) {
        global $koneksi;
        $query = "DELETE FROM books WHERE id = " . $id;
        if(mysqli_query($koneksi, $query)) {
            $response = array(
                'status' => 200,
                'message' => 'Success'
            );
        } else {
            $response = array(
                'status' => 500,
                'message' => 'Internal server error'
            );
        };
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}