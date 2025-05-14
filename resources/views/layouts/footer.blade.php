<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Polars</h5>
                <p class="small">Ruang pamer virtual mahasiswa dan dosen Program Studi Teknologi Rekayasa Multimedia.</p>
            </div>
            <div class="col-md-4">
                <h5>Links</h5>
                <ul class="list-unstyled">
                    <li><a href="/" class="text-white">Home</a></li>
                    <li><a href="{{ route('posts.index') }}" class="text-white">Gallery</a></li>
                    <li><a href="{{ route('profile.index') }}" class="text-white">Profile</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Contact</h5>
                <address class="small">
                    Program Studi Teknologi Rekayasa Multimedia<br>
                    Politeknik Negeri Media Kreatif<br>
                    <i class="bi bi-envelope-fill"></i> multimedia@polimedia.ac.id<br>
                    <i class="bi bi-telephone-fill"></i> (021) 7997777
                </address>
            </div>
        </div>
        <div class="border-top pt-3 mt-3 text-center">
            <p class="small mb-0">&copy; {{ date('Y') }} Polars | Polimedia Kreatif</p>
        </div>
    </div>
</footer>
