<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    projects: Array
});
</script>

<template>
    <Head title="All Projects - Atala Aditia Anwar" />

    <div class="min-h-screen bg-[#0a0a0a] text-white selection:bg-blue-500/30">
        <nav class="fixed top-0 w-full z-50 flex justify-between items-center px-10 py-8 backdrop-blur-md bg-black/40 border-b border-white/5">
            <Link href="/" class="text-3xl font-black tracking-tighter italic">AA.</Link> 
            <div class="hidden md:flex gap-12 text-[10px] font-bold uppercase tracking-[0.3em] text-gray-500">
                <a href="/#about" class="hover:text-white transition">About Me</a>
                <Link :href="route('projects.index')" class="text-white transition">Portfolio</Link>
                <Link href="/blog" class="hover:text-white transition">Blog</Link>
            </div>
            <a href="mailto:aditia.anwar08@gmail.com" class="border border-white/20 px-8 py-3 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-white hover:text-black transition duration-500">
                Contact Me
            </a>
        </nav>

        <section class="pt-40 pb-20">
            <div class="container mx-auto px-10">
                <div class="flex flex-col mb-24">
                    <h1 class="text-7xl lg:text-9xl font-bold tracking-tighter">All <br/><span class="text-neutral-800 italic">Work</span></h1>
                    <p class="text-gray-500 max-w-sm mt-8 text-lg leading-relaxed">
                        A comprehensive list of my professional journey in Quality Assurance and Automation.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-32">
                    <div v-for="project in projects" :key="project.id" class="group">
                        <a :href="project.link" target="_blank" class="block aspect-video bg-neutral-900 rounded-[2rem] overflow-hidden mb-8 border border-white/5 relative">
                            <img v-if="project.image" :src="'/storage/' + project.image" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" />
                            <div v-else class="absolute inset-0 flex items-center justify-center text-8xl font-black italic opacity-10">
                                {{ project.title.substring(0,2) }}
                            </div>
                        </a>
                        
                        <div class="flex justify-between items-start px-2">
                            <div>
                                <a :href="project.link" target="_blank" class="hover:text-blue-500 transition-colors">
                                    <h3 class="text-3xl font-bold mb-3">{{ project.title }}</h3>
                                </a>
                                <p class="text-gray-400 mb-4 line-clamp-2 max-w-md">{{ project.description }}</p>
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="tech in project.tech_stack.split(',')" :key="tech" class="text-[8px] border border-white/10 px-3 py-1 rounded-full uppercase text-gray-500">
                                        {{ tech.trim() }}
                                    </span>
                                </div>
                            </div>
                            <a :href="project.link" target="_blank" class="text-3xl hover:translate-x-2 transition">↗</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="py-20 text-center border-t border-white/5">
            <p class="text-gray-600 text-[10px] uppercase tracking-[0.5em]">© 2026 Atala Aditia Anwar — Built with ❤️ and Laravel</p>
        </footer>
    </div>
</template>