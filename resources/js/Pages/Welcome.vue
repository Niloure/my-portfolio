<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted } from 'vue';
import AtalaFoto from '@/Components/AtalaFoto.vue';

const props = defineProps({
    name: String,
    role: String,
    projects: Array,
    socials: Array,
    stacks: Array
});

const latestProjects = computed(() => {
    return props.projects ? props.projects.slice(0, 3) : [];
});

const completedProjects = computed(() => {
    return props.projects ? props.projects.length : 0;
});

const scrollToPortfolio = () => {
    document.getElementById('portfolio').scrollIntoView({ behavior: 'smooth' });
};

const showScrollTop = ref(false);

const handleScroll = () => {
    showScrollTop.value = window.scrollY > 400;
};

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(() => window.addEventListener('scroll', handleScroll));
onUnmounted(() => window.removeEventListener('scroll', handleScroll));
</script>

<template>
    <Head title="Portfolio - Atala Aditia Anwar" />

    <!-- Social Sidebar -->
    <div class="fixed left-8 bottom-0 z-[60] hidden lg:flex flex-col gap-8 after:h-24 after:w-[1px] after:bg-gray-800 after:mx-auto">
        <a
            v-for="social in socials"
            :key="social.id"
            :href="social.value"
            target="_blank"
            class="text-[10px] font-bold uppercase tracking-[0.4em] text-gray-500 hover:text-white transition rotate-180 [writing-mode:vertical-lr] mb-4"
        >
            {{ social.key }}
        </a>
    </div>

    <div class="bg-[#0a0a0a] text-white selection:bg-blue-500/30">

        <!-- Navbar -->
        <nav class="fixed top-0 w-full z-[70] flex justify-between items-center px-10 py-8 backdrop-blur-md bg-black/40 border-b border-white/5">
            <div class="text-3xl font-black tracking-tighter italic">
                AA.
            </div>

            <div class="hidden md:flex gap-12 text-[10px] font-bold uppercase tracking-[0.3em] text-gray-500">
                <a href="#about" class="hover:text-white transition">
                    About Me
                </a>

                <Link :href="route('projects.index')" class="hover:text-white transition">
                    Portfolio
                </Link>

                <Link href="/blog" class="hover:text-white transition">
                    Blog
                </Link>
            </div>

            <a
                href="mailto:aditia.anwar08@gmail.com"
                class="border border-white/20 px-8 py-3 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-white hover:text-black transition duration-500"
            >
                Contact Me
            </a>
        </nav>

        <main class="relative">

            <!-- Hero Section -->
            <section
                id="about"
                class="sticky top-0 h-screen flex items-stretch z-10 overflow-hidden bg-[#0a0a0a]"
            >
                <div class="grid grid-cols-1 lg:grid-cols-12 w-full h-full">

                    <!-- LEFT CONTENT -->
                    <div class="lg:col-span-6 flex flex-col justify-between pl-20 lg:pl-32 pr-10 pt-40 pb-16 h-full z-20">

                        <div>

                            <!-- Stats -->
                            <div class="flex gap-16 mb-6">

                                <div>
                                    <span class="block text-4xl font-bold text-white tracking-tighter">
                                        +{{ completedProjects }}
                                    </span>

                                    <span class="text-[8px] uppercase tracking-[0.2em] text-gray-600 font-bold">
                                        Project completed
                                    </span>
                                </div>

                                <div>
                                    <span class="block text-4xl font-bold text-white tracking-tighter">
                                        -/+ 1
                                    </span>

                                    <span class="text-[8px] uppercase tracking-[0.2em] text-gray-600 font-bold">
                                        Years Experience
                                    </span>
                                </div>

                            </div>

                            <!-- Heading -->
                            <h1 class="text-[7rem] lg:text-[10rem] font-bold leading-[0.8] tracking-tighter mb-8 text-white">
                                Hello
                            </h1>

                            <!-- Description -->
                            <p class="text-xl lg:text-2xl text-gray-400 max-w-xl leading-relaxed font-light">
                                — It's
                                <span class="text-white font-semibold">
                                    {{ name }}
                                </span>,
                                a tech enthusiast who loves

                                <span class="text-white font-semibold">
                                    building scalable systems
                                </span>

                                and

                                <span class="text-white font-semibold">
                                    ensuring digital excellence
                                </span>.
                            </p>

                            <!-- Stack -->
                            <div class="mt-8 flex flex-wrap gap-3">

                                <a
                                    v-for="stack in stacks"
                                    :key="stack.id"
                                    :href="stack.url"
                                    target="_blank"
                                    class="px-4 py-2 border border-white/10 rounded-full text-[9px] uppercase tracking-widest font-bold hover:bg-white hover:text-black hover:border-white transition-all duration-300 opacity-50 hover:opacity-100"
                                >
                                    {{ stack.name }}
                                </a>

                            </div>

                        </div>

                        <!-- Scroll Button -->
                        <div class="pt-10">
                            <button
                                @click="scrollToPortfolio"
                                class="inline-flex items-center gap-4 text-[10px] font-bold uppercase tracking-[0.4em] text-gray-600 hover:text-white transition group"
                            >
                                Scroll down

                                <span class="text-lg group-hover:translate-y-2 transition-transform duration-500">
                                    ↓
                                </span>
                            </button>
                        </div>

                    </div>

                    <!-- RIGHT IMAGE -->
                    <div class="lg:col-span-6 relative h-full flex items-center justify-center">

                        <div
                            class="relative w-[82%] h-[88%] lg:w-[78%] lg:h-[88%] grayscale hover:grayscale-0 transition duration-1000 ease-in-out"
                        >
                            <AtalaFoto
                                class="w-full h-full object-cover rounded-[2rem] object-center opacity-80"
                            />
                        </div>

                        <!-- Gradient -->
                        <div class="absolute inset-0 bg-gradient-to-r from-[#0a0a0a] via-[#0a0a0a]/20 to-transparent lg:block hidden"></div>

                        <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-transparent to-transparent opacity-90"></div>

                        <!-- Side Text -->
                        <div class="absolute right-10 bottom-12 z-30">
                            <span class="rotate-90 block origin-bottom-right text-[10px] font-bold uppercase tracking-[1em] text-gray-800 whitespace-nowrap">
                                2026 / NILOURÈ STUDIO
                            </span>
                        </div>

                    </div>

                </div>
            </section>

            <!-- Portfolio -->
            <section
                id="portfolio"
                class="relative z-20 py-20 bg-[#0d0d0d] border-t border-white/5 rounded-t-[5rem] shadow-[0_-50px_100px_rgba(0,0,0,1)]"
            >
                <div class="container mx-auto px-10">

                    <div class="flex justify-between items-end mb-14">

                        <h2 class="text-5xl lg:text-7xl font-bold tracking-tighter leading-none">
                            Latest <br />
                            <span class="text-neutral-800 italic">
                                Project
                            </span>
                        </h2>

                        <Link
                            :href="route('projects.index')"
                            class="text-sm border-b border-white/20 pb-2 hover:text-blue-500 transition uppercase tracking-widest"
                        >
                            View All
                        </Link>

                    </div>

                    <!-- Projects -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                        <div
                            v-for="project in latestProjects"
                            :key="project.id"
                            class="group relative aspect-[4/5] bg-neutral-900 rounded-[2.5rem] overflow-hidden border border-white/5"
                        >
                            <img
                                v-if="project.image"
                                :src="'/storage/' + project.image"
                                class="w-full h-full object-cover transition duration-700 group-hover:scale-110 group-hover:blur-sm"
                            />

                            <div class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-10 text-left">

                                <h3 class="text-3xl font-bold mb-4 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                    {{ project.title }}
                                </h3>

                                <div class="flex flex-wrap gap-2 mb-6 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-75">

                                    <span
                                        v-for="tech in project.tech_stack.split(',')"
                                        :key="tech"
                                        class="text-[8px] bg-white/10 px-3 py-1 rounded-full uppercase text-white font-bold backdrop-blur-md"
                                    >
                                        {{ tech }}
                                    </span>

                                </div>

                                <a
                                    :href="project.link"
                                    target="_blank"
                                    class="text-[10px] font-bold uppercase tracking-widest text-blue-400 hover:text-white transition"
                                >
                                    Explore Project ↗
                                </a>

                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="relative z-20 py-20 text-center bg-[#0d0d0d] border-t border-white/5">
                <p class="text-gray-600 text-[10px] uppercase tracking-[0.5em]">
                    © 2026 Atala Aditia Anwar — Built with ❤️ and Laravel
                </p>
            </footer>

        </main>

        <!-- Scroll To Top -->
        <Transition name="fade">
            <button
                v-show="showScrollTop"
                @click="scrollToTop"
                class="fixed bottom-10 right-10 z-[100] w-14 h-14 bg-white text-black rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-all duration-300"
            >
                <span class="text-xl">↑</span>
            </button>
        </Transition>

    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.4s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

html {
    scroll-behavior: smooth;
}
</style>