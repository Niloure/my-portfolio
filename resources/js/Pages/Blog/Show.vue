<template>
    <Head :title="post.title + ' - Atala Aditia Anwar'" />

    <div class="bg-[#0a0a0a] min-h-screen text-white selection:bg-indigo-500/30">
        <nav class="p-10 lg:px-32 lg:py-10">
            <Link :href="route('blog.index')" class="text-gray-500 hover:text-white transition uppercase tracking-[0.2em] text-[10px] font-bold">
                ← Back to Journal
            </Link>
        </nav>

        <main class="max-w-4xl mx-auto px-6 pb-20">
            <header class="mb-16">
                <div class="flex items-center gap-3 mb-6">
                    <span class="h-[1px] w-8 bg-indigo-600"></span>
                    <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">
                        Published on {{ new Date(post.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                    </span>
                </div>
                
                <h1 class="text-4xl lg:text-6xl font-black tracking-tight leading-[1.1]">
                    {{ post.title }}
                </h1>
            </header>

            <article 
                v-html="post.content" 
                class="prose prose-invert prose-lg max-w-none 
                       prose-headings:text-white prose-headings:tracking-tight
                       prose-p:text-gray-300 prose-p:leading-relaxed
                       prose-strong:text-white prose-strong:font-bold
                       prose-img:rounded-2xl prose-img:shadow-2xl prose-img:mx-auto
                       prose-a:text-indigo-400 prose-a:no-underline hover:prose-a:text-white prose-a:transition
                       prose-li:text-gray-300"
            >
            </article>

            <footer class="mt-20 pt-10 border-t border-gray-800/50">
                <p class="text-gray-500 text-sm italic font-light">
                    Ditulis oleh Atala Aditia Anwar sebagai bagian dari dokumentasi teknis dan perjalanan personal.
                </p>
            </footer>
        </main>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    post: Object
});
</script>

<style>
/* LIST */
.prose ul {
    list-style-type: disc;
    padding-left: 1.5rem;
}

.prose ol {
    list-style-type: decimal;
    padding-left: 1.5rem;
}

/* BLOCKQUOTE */
.prose blockquote {
    border-left: 4px solid #4f46e5;
    padding-left: 1rem;
    font-style: italic;
    color: #9ca3af;
}

/* HEADING QUILL */
.prose h1 {
    font-size: 2.5rem;
    line-height: 1.2;
    font-weight: 800;
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: white;
}

.prose h2 {
    font-size: 2rem;
    line-height: 1.3;
    font-weight: 700;
    margin-top: 1.8rem;
    margin-bottom: 0.8rem;
    color: white;
}

.prose h3 {
    font-size: 1.5rem;
    line-height: 1.4;
    font-weight: 700;
    margin-top: 1.5rem;
    margin-bottom: 0.7rem;
    color: white;
}
</style>