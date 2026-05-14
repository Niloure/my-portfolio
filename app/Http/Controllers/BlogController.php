<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Mews\Purifier\Facades\Purifier;

class BlogController extends Controller
{
    public function index()
    {
        return Inertia::render('Blog/Index', [
            'posts' => Post::latest()->get()
        ]);
    }

    public function show(Post $post)
    {
        return Inertia::render('Blog/Show', [
            'post' => $post
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        try {
            $finalContent = $this->processContentImages($validated['content']);
        } catch (\Exception $e) {
            return back()->withErrors([
                'content' => $e->getMessage()
            ]);
        }
        
        // sanitize HTML
        $cleanHtml = Purifier::clean($finalContent);

        Post::create([
            'title' => $validated['title'],
            'content' => $cleanHtml,
            'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
        ]);

        return back()->with('message', 'Artikel berhasil dibuat!');
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        // ambil gambar lama sebelum update
        $oldImages = $this->extractImagesFromContent($post->content);

        $finalContent = $this->processContentImages($validated['content']);

        // sanitize HTML
        $cleanHtml = Purifier::clean($finalContent);

        $post->update([
            'title' => $validated['title'],
            'content' => $cleanHtml,
            'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
        ]);

        // ambil gambar baru
        $newImages = $this->extractImagesFromContent($cleanHtml);

        // hapus gambar yang sudah tidak dipakai
        foreach ($oldImages as $image) {
            if (!in_array($image, $newImages)) {
                $path = str_replace(asset('storage') . '/', '', $image);

                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        return back()->with('message', 'Artikel berhasil diperbarui!');
    }

    public function destroy(Post $post)
    {
        // hapus semua image terkait post
        $images = $this->extractImagesFromContent($post->content);

        foreach ($images as $image) {
            $path = str_replace(asset('storage') . '/', '', $image);

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $post->delete();

        return back()->with('message', 'Artikel berhasil dihapus!');
    }

    /**
     * Process base64 images in content
     */
    private function processContentImages($content)
    {
        $dom = new \DOMDocument();

        libxml_use_internal_errors(true);

        @$dom->loadHTML(
            mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $images = $dom->getElementsByTagName('img');

        $allowedMime = ['png', 'jpg', 'jpeg', 'webp'];

        /** @var \DOMElement $img */
        foreach ($images as $img) {

            $src = $img->getAttribute('src');

            // hanya proses base64 image
            if (strpos($src, 'data:image') === 0) {

                preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);

                $mimetype = strtolower($groups['mime'] ?? 'png');

                // validasi mime
                if (!in_array($mimetype, $allowedMime)) {
                    continue;
                }

                $data = explode(',', $src);

                $decodedImage = base64_decode($data[1]);

                // limit 2MB
                if (strlen($decodedImage) > 2 * 1024 * 1024) {
                    throw new \Exception('Ukuran gambar melebihi 2MB');
                }

                $fileName = Str::random(20) . '_' . time() . '.' . $mimetype;

                Storage::disk('public')->put(
                    'blog-images/' . $fileName,
                    $decodedImage
                );

                $img->setAttribute(
                    'src',
                    asset('storage/blog-images/' . $fileName)
                );
            }
        }

        return $dom->saveHTML();
    }

    /**
     * Extract image URLs from HTML content
     */
    private function extractImagesFromContent($content)
    {
        $dom = new \DOMDocument();

        libxml_use_internal_errors(true);

        @$dom->loadHTML(
            mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $images = $dom->getElementsByTagName('img');

        $imageUrls = [];

        /** @var \DOMElement $img */
        foreach ($images as $img) {
            $imageUrls[] = $img->getAttribute('src');
        }

        return $imageUrls;
    }
}