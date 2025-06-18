<footer class="footer-section">
    <div class="container relative">

        <div class="row mb-5">
            <div class="col-lg-4">
                <div class="mb-4 footer-logo-wrap"><a href="#" class="footer-logo">Nuansa<span>.</span></a></div>
                <p class="mb-4">Kami percaya bahwa kenyamanan bukan sekadar pilihan, tapi gaya hidup. Yuk, ubah ruangmu jadi tempat terbaik untuk recharge energi.</p>

                <ul class="list-unstyled custom-social">
                    <li><a href="https://www.instagram.com/nuansa_beanbags?igsh=MWN0cjF6cDIwMWxnNw%3D%3D"><span class="fa fa-brands fa-instagram"></span></a></li>
                </ul>
            </div>

            <div class="col-lg-8">
                <div class="row links-wrap justify-content-end">
                    <div class="col-6 col-sm-6 col-md-3">
                        <ul class="list-unstyled">
                            <li><a href="<?= base_url(route_to('landing.about')) ?>">Tentang kami</a></li>
                            <li><a href="<?= base_url(route_to('landing.service')) ?>">Layanan</a></li>
                            <li><a href="<?= base_url(route_to('landing.contact')) ?>">Kontak</a></li>
                        </ul>
                    </div>

                    <div class="col-6 col-sm-6 col-md-3">
                        <ul class="list-unstyled">
                            <?php foreach ($featured_products as $product) : ?>
                                <li><a href="<?= base_url(route_to('landing.shop')) ?>"><?= $product['name'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <div class="border-top copyright">
            <div class="row pt-4">
                <div class="col-lg-6">
                    <p class="mb-2 text-center text-lg-start">Nuansa &copy;<script>
                            document.write(new Date().getFullYear());
                        </script>. All Rights Reserved.
                    </p>
                </div>
            </div>
        </div>

    </div>
</footer>