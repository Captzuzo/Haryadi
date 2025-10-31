<?php \('layouts.main'); ?>

<?php \('title'); ?>
Haryadi - Beranda
<?php $__env->stopSection(); ?>

<?php \('content'); ?>
    <section class="hero">
        <h1>Selamat datang di Haryadi</h1>
        <p>Framework PHP kecil dan edukasional.</p>
    </section>

    <section class="contact">
        <h2>Kontak</h2>
        <form method="post" action="/contact/submit">
            <?php echo csrf_field(); ?>
            <div>
                <label for="name">Nama</label>
                <input id="name" name="name" type="text">
            </div>
            <div>
                <label for="message">Pesan</label>
                <textarea id="message" name="message"></textarea>
            </div>
            <button type="submit">Kirim</button>
        </form>
    </section>
<?php $__env->stopSection(); ?>