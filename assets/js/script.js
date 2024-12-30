// assets/js/script.js

// format angka ke rupiah (tanpa 'Rp' dan desimal)
function formatRupiah(angka) {
    if (angka === '' || angka === null) return '';
    return new Intl.NumberFormat('id-ID').format(angka);
}

// membersihkan format rupiah menjadi angka
function cleanRupiah(str) {
    if (typeof str !== 'string') return '';
    return str.replace(/\D/g, '');
}

// mengatur format input harga
function setupPriceInput(input) {

    input.type = 'text';
    

    input.addEventListener('input', function(e) {

        const cursorPos = this.selectionStart;
        const prevLength = this.value.length;
        
        let value = cleanRupiah(this.value);
        
        this.dataset.value = value;
        

        this.value = value ? formatRupiah(value) : '';
        

        const newLength = this.value.length;
        const newPos = cursorPos + (newLength - prevLength);
        this.setSelectionRange(newPos, newPos);
    });


    input.addEventListener('blur', function(e) {
        if (!this.value) {
            this.value = formatRupiah(0);
            this.dataset.value = '0';
        }
    });


    if (input.value) {
        const value = cleanRupiah(input.value);
        input.dataset.value = value;
        input.value = formatRupiah(value);
    }
}

// Setup format harga untuk semua input harga
document.addEventListener('DOMContentLoaded', function() {

    const priceInputs = document.querySelectorAll('input[name="buy_price"], input[name="sell_price"]');
    priceInputs.forEach(input => setupPriceInput(input));


    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });

    // Validasi form produk
    const productForm = document.querySelector('form');
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            const buyPriceInput = document.querySelector('input[name="buy_price"]');
            const sellPriceInput = document.querySelector('input[name="sell_price"]');
            
            if (!buyPriceInput || !sellPriceInput) return;


            const buyPrice = parseInt(buyPriceInput.dataset.value || 0);
            const sellPrice = parseInt(sellPriceInput.dataset.value || 0);
            

            if (sellPrice < buyPrice) {
                e.preventDefault();
                alert('Harga jual tidak boleh lebih kecil dari harga beli!');
                return;
            }
            

            buyPriceInput.value = buyPrice;
            sellPriceInput.value = sellPrice;
        });
    }

    // Auto-hide alert messages setelah 5 detik
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});

// Tambahkan event listener untuk mencegah input karakter non-angka
document.addEventListener('keypress', function(e) {
    if (e.target.matches('input[name="buy_price"], input[name="sell_price"]')) {
        const char = String.fromCharCode(e.which);
        if (!/[0-9]/.test(char)) {
            e.preventDefault();
        }
    }
});