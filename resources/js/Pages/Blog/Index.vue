<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({ 
    posts: Array 
});
</script>

<template>
    <Head title="Blog - Atala Aditia Anwar" />

    <div class="min-h-screen bg-[#0a0a0a] text-white selection:bg-blue-500/30">
        <!-- Navbar -->
        <nav class="fixed top-0 w-full z-50 flex justify-between items-center px-6 md:px-10 py-6 backdrop-blur-md bg-black/40 border-b border-white/5">
            <Link href="/" class="text-3xl font-black tracking-tighter italic">AA.</Link> 
            
            <div class="hidden md:flex gap-12 text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400">
                <Link href="/" class="hover:text-white transition">About Me</Link>
                <!-- Pastikan route 'projects.index' sudah ada di web.php -->
                <Link :href="route('projects.index')" class="hover:text-white transition">Portfolio</Link>
                <Link :href="route('blog.index')" class="text-white transition">Blog</Link>
            </div>

            <a href="mailto:aditia.anwar08@gmail.com" class="border border-white/20 px-6 py-2.5 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-white hover:text-black transition duration-500">
                Contact Me
            </a>
        </nav>

        <!-- Main Content Container -->
        <main class="max-w-7xl mx-auto px-6 md:px-10 pt-40 pb-20">
            <!-- Header Section -->
            <header class="relative mb-24">
                <h1 class="text-7xl lg:text-9xl font-bold tracking-tighter italic opacity-10 absolute -top-16 -left-2 select-none pointer-events-none">
                    JOURNAL
                </h1>
                <h2 class="relative text-5xl font-bold tracking-tight">Latest Thoughts</h2>
                <div class="h-1 w-20 bg-blue-600 mt-4"></div>
            </header>

            <!-- Blog Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-20">
                <div v-for="post in posts" :key="post.id" class="group">
                    <Link :href="route('blog.show', post.slug || post.id)">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="text-blue-500 text-[10px] font-bold uppercase tracking-widest">Article</span>
                            <span class="h-[1px] w-8 bg-gray-800"></span>
                            <span class="text-gray-600 text-xs font-mono">
                                {{ new Date(post.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                            </span>
                        </div>

                        <h3 class="text-3xl font-bold group-hover:text-blue-500 transition-colors duration-300 tracking-tight leading-tight">
                            {{ post.title }}
                        </h3>

                        <!-- Preview Content (stripped tags for safety/cleanliness) -->
                        <div class="text-gray-400 mt-4 font-light leading-relaxed line-clamp-3 overflow-hidden">
                            <div v-html="post.content"></div>
                        </div>

                        <!-- Animated Underline -->
                        <div class="mt-8 h-[1px] w-12 bg-gray-800 group-hover:w-full group-hover:bg-blue-500 transition-all duration-700"></div>
                    </Link>
                </div>

                <!-- Empty State jika tidak ada post -->
                <div v-if="posts.length === 0" class="col-span-full py-20 text-center border border-dashed border-white/10 rounded-2xl">
                    <p class="text-gray-500 font-mono italic">Belum ada tulisan yang diterbitkan.</p>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
/* Menghilangkan styling default dari v-html agar tidak merusak layout list */
:deep(p) {
    margin-bottom: 0.5rem;
}
:deep(img) {
    display: none; /* Sembunyikan gambar di preview list agar rapi */
}
</style>