function deretFibonacci(n) {
    let fibonacci = [0, 1]; //Inisialisasi awal deret 
    for (let i = 2; i < n; i++) {//Perulangan untuk deret fibonacci selanjutnya
        fibonacci[i] = fibonacci[i - 2] + fibonacci[i - 1];
    }
    return fibonacci;
}

console.log("Deret Fibonacci:", deretFibonacci(5));