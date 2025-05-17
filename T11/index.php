<!-- OOP PHP (TUGAS PERTEMUAN 11) -->
<?php

//class book beserta atribut dan method nya 
class Book {

    //atribut class book
    private $code_book;
    private $name;
    private $qty;
    private $isValid = true; //bool untuk mengecek validasi atribut 

    //konstruktor, otomatis dipanggil saat objek kelas diinisialisasi 
    public function __construct($code_book, $name, $qty) {
        if (!$this->setCodeBook($code_book)) { //pengkondisian jika code book tidak valid 
            $this->isValid = false; //var isValid di set false 
        }

        $this->name = $name; //Inisialisasi nama 

        if (!$this->setQty($qty)) { //pengkondisian jika qty book tidak valid 
            $this->isValid = false;
        }
    }

    //Fungsi setter privat untuk menginisialisasi kode buku 
    private function setCodeBook($code) {
        //Pengkondisian agar kode buku memenuhi format 2 huruf besar + 2 angka 
        if (preg_match('/^[A-Z]{2}[0-9]{2}$/', $code)) {
            $this->code_book = $code;
            return true;
        } else { //Pesan error jika format code book tidak valid
            echo "Error: Format code_book harus 2 huruf besar diikuti 2 angka. Contoh: BB00\n";
            return false;
        }
    }

    //Fungsi setter privat untuk menginisialisasi qty  
    private function setQty($qty) {
         //Pengkondisian agar qty berupa integer positif 
        if (is_int($qty) && $qty > 0) {
            $this->qty = $qty;
            return true;
        } else { //Pesan error jika format qty tidak valid
            echo "Error: qty harus integer positif.\n";
            return false;
        }
    }


    //Fungsi untuk mengecek variabel bool isValid
    public function isValid() {
        return $this->isValid;
    }

    //fungsi getter untuk code book 
    public function getCodeBook() {
        return $this->code_book; //mengembalikan nilai berupa code book 
    }

    //fungsi getter untuk qty
    public function getQty() {
        return $this->qty; //mengembalikan nilai berupa qty 
    }

    //fungsi getter untuk name (opsional, tidak ada pada soal)
    public function getName() {
        return $this->name; //mengembalikan nilai berupa name
    }
}

// Contoh object dari kelas book 
$book1 = new Book("AC22", "Hujan", 5);

//Mencetak informasi buku jika valid 
if ($book1->isValid()) {
    echo "Kode Buku: " . $book1->getCodeBook() . "\n";
    echo "Nama Buku: " . $book1->getName() . "\n";
    echo "Jumlah: " . $book1->getQty() . "\n";
} else { //Cetak pesan di bawah error jika informasi buku tidak valid 
    echo "Informasi buku " . $book1->getName()  . " tidak valid";
}

//contoh tidak valid
$book2 = new Book("Ac22", "Ayah", -1);

if ($book2->isValid()) {
    echo "Kode Buku: " . $book2->getCodeBook() . "\n";
    echo "Nama Buku: " . $book2->getName() . "\n";
    echo "Jumlah: " . $book2->getQty() . "\n";
} else { //Cetak pesan di bawah error jika informasi buku tidak valid 
    echo "Informasi buku " . $book2->getName()  . " tidak valid";
}

