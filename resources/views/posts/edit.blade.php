@extends('layouts.app')

@section('content')
    <div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-lg border-0 w-100" style="max-width: 600px;">
            <div class="card-body p-5">
                <h2 class="mb-4 text-2xl text-center fw-bold" style="letter-spacing:1px;">Edit Postingan</h2>
                <form action="{{ route('posts.update', $post) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="title" class="form-label fw-semibold">Judul</label>
                        <input type="text" name="title" id="title" class="form-control w-full form-control-lg rounded-3"
                            value="{{ old('title', $post->title) }}" required autofocus>
                        @error('title')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">Isi Postingan</label>
                        <textarea name="description" id="description" class="form-control w-full form-control-lg rounded-3"
                            rows="7" required>{{ old('description', $post->description) }}</textarea>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tambahkan field lain jika ada (misal kategori, hashtag, dsb) -->

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">Update
                            Postingan</button>
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-secondary px-4 py-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection