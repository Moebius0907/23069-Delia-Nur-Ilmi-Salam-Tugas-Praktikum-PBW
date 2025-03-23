//Kalkulator 
const calculator = (operator, ...numbers) => {
    switch (operator) {
        case "+":
            return numbers.reduce((a, b) => a + b);
        case "-":
            return numbers.reduce((a, b) => a - b);
        case "*":
            return numbers.reduce((a, b) => a * b);
        case "/":
            return numbers.reduce((a, b) => a / b);
        case "%":
            return numbers.reduce((a, b) => a % b);
        default:
            return "Operator tidak valid!";
    }
};

console.log("Penjumlahan:", calculator("+", 10, 6, )); 
console.log("Pengurangan:", calculator("-", 2, 3));   
console.log("Perkalian:", calculator("*", 4, 2, ));   
console.log("Pembagian:", calculator("/", 25, 2));   
console.log("Modulus:", calculator("%", 20, 3));      