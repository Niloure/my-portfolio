<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'

const quillRef = ref(null)

const MAX_IMAGE_SIZE = 2 * 1024 * 1024 // 2MB

const imageHandler = () => {
    const input = document.createElement('input')
    input.setAttribute('type', 'file')
    input.setAttribute('accept', 'image/*')
    input.click()

    input.onchange = () => {
        const file = input.files[0]

        if (!file) return

        if (file.size > MAX_IMAGE_SIZE) {
            alert('Ukuran gambar maksimal 2MB!')
            return
        }

        const reader = new FileReader()

        reader.onload = (e) => {
            const quill = quillRef.value.getQuill()
            const range = quill.getSelection(true)

            quill.insertEmbed(
                range.index,
                'image',
                e.target.result
            )

            quill.setSelection(range.index + 1)
        }

        reader.readAsDataURL(file)
    }
}

const setImageSize = (size) => {

    const quill = quillRef.value.getQuill()

    const range = quill.getSelection(true)

    if (!range) {
        alert('Klik gambar dulu')
        return
    }

    const [blot] = quill.getLeaf(range.index)

    if (!blot || blot.domNode.tagName !== 'IMG') {
        alert('Pilih gambar dulu')
        return
    }

    const img = blot.domNode

    img.setAttribute(
        'style',
        `
        width:${size};
        height:auto;
        display:block;
        margin:20px auto;
        border-radius:14px;
        `
    )

    // PAKSA UPDATE KE V-MODEL
    blogForm.content = quill.root.innerHTML
}

const applyImageStylesToContent = () => {
    const tempDiv = document.createElement('div')
    tempDiv.innerHTML = blogForm.content

    const images = tempDiv.querySelectorAll('img')

    images.forEach((img) => {

        if (img.style.width) {
            img.setAttribute(
                'style',
                `
                width:${img.style.width};
                height:auto;
                display:block;
                margin:20px auto;
                border-radius:14px;
                `
            )
        }

    })

    blogForm.content = tempDiv.innerHTML
}

const editorOptions = {
    theme: 'snow',
    modules: {
        toolbar: {
            container: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                ['blockquote', 'code-block'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link', 'image'],
                ['clean']
            ],
            handlers: {
                image: imageHandler
            }
        }
    }
}

const validateImagesInContent = (html) => {
    const matches = html.match(/data:image\/.*?;base64,(.*?)(?=")/g)

    if (!matches) return true

    for (const match of matches) {
        const base64 = match.split(',')[1]

        const sizeInBytes = (base64.length * 3) / 4

        if (sizeInBytes > MAX_IMAGE_SIZE) {
            alert('Ukuran gambar melebihi 2MB!')
            return false
        }
    }

    return true
}

defineProps({
    projects: Array,
    stacks: Array,
    socials: Array,
    posts: Array
})

const blogForm = useForm({
    title: '',
    content: ''
})

const isEditingBlog = ref(false)
const currentBlogId = ref(null)

const submitBlog = () => {

    applyImageStylesToContent()

    if (!validateImagesInContent(blogForm.content)) {
        return
    }

    if (isEditingBlog.value) {

        blogForm.patch(route('blog.update', currentBlogId.value), {
            onSuccess: () => {

                blogForm.reset()

                const quill = quillRef.value.getQuill()
                quill.setContents([])

                isEditingBlog.value = false

                alert('Blog berhasil diupdate!')
            }
        })

    } else {

        blogForm.post(route('blog.store'), {
            onSuccess: () => {

                blogForm.reset()

                const quill = quillRef.value.getQuill()
                quill.setContents([])

                alert('Blog berhasil diterbitkan!')
            }
        })

    }
}

const editBlog = (post) => {
    isEditingBlog.value = true
    currentBlogId.value = post.id
    blogForm.title = post.title
    blogForm.content = post.content

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    })
}

const showingModal = ref(false)
const isEditing = ref(false)
const currentId = ref(null)

const form = useForm({
    title: '',
    description: '',
    tech_stack: '',
    link: '',
    image: null,
})

const openCreateModal = () => {
    isEditing.value = false
    form.reset()
    showingModal.value = true
}

const openEditModal = (project) => {
    isEditing.value = true
    currentId.value = project.id

    form.title = project.title
    form.description = project.description
    form.tech_stack = project.tech_stack
    form.link = project.link

    showingModal.value = true
}

const submit = () => {

    if (isEditing.value) {

        form.transform((data) => ({
            ...data,
            _method: 'patch'
        }))
        .post(route('projects.update', currentId.value), {
            onSuccess: () => {
                showingModal.value = false
                form.reset()
            },
        })

    } else {

        form.post(route('projects.store'), {
            onSuccess: () => {
                showingModal.value = false
                form.reset()
            },
        })

    }
}

const deleteProject = (id) => {
    if (confirm('Hapus proyek ini?')) {
        form.delete(route('projects.destroy', id))
    }
}

const stackForm = useForm({
    name: '',
    url: ''
})

const socialForm = useForm({
    key: '',
    value: ''
})

const addStack = () =>
    stackForm.post(route('stacks.store'), {
        onSuccess: () => stackForm.reset()
    })

const deleteStack = (id) => {
    if (confirm('Hapus stack?')) {
        stackForm.delete(route('stacks.destroy', id))
    }
}

const addSocial = () =>
    socialForm.post(route('socials.store'), {
        onSuccess: () => socialForm.reset()
    })

const deleteSocial = (id) => {
    if (confirm('Hapus sosmed?')) {
        socialForm.delete(route('socials.destroy', id))
    }
}
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div class="py-12 bg-gray-900 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                <div class="bg-gray-800 border border-gray-700 shadow-2xl rounded-2xl p-8 mt-8">
                    <h2 class="text-xl font-bold text-white mb-6">Manage Blog Posts</h2>
                    
                    <form @submit.prevent="submitBlog" class="space-y-4 mb-8">
                        <TextInput v-model="blogForm.title" placeholder="Post Title" class="w-full bg-gray-900 text-white" required />
                        
                        <div class="bg-white rounded-md overflow-hidden min-h-[300px] text-black">
                            <QuillEditor
                                ref="quillRef"
                                v-model:content="blogForm.content"
                                @update:content="blogForm.content = $event"
                                contentType="html"
                                :options="editorOptions"
                                placeholder="Tulis cerita atau upload gambar..."
                                class="min-h-[250px] custom-quill"
                            />
                        </div>
                        <div class="flex gap-2 mt-2">
                            <button
                                type="button"
                                @click="setImageSize('200px')"
                                class="px-3 py-1 bg-gray-700 text-white rounded"
                            >
                                Small
                            </button>

                            <button
                                type="button"
                                @click="setImageSize('400px')"
                                class="px-3 py-1 bg-gray-700 text-white rounded"
                            >
                                Medium
                            </button>

                            <button
                                type="button"
                                @click="setImageSize('700px')"
                                class="px-3 py-1 bg-gray-700 text-white rounded"
                            >
                                Large
                            </button>

                            <button
                                type="button"
                                @click="setImageSize('100%')"
                                class="px-3 py-1 bg-gray-700 text-white rounded"
                            >
                                Full
                            </button>
                        </div>
                        <p v-if="blogForm.errors.content" class="text-red-500 text-sm mt-2">
                            {{ blogForm.errors.content }}
                        </p>

                        <PrimaryButton>{{ isEditingBlog ? 'Update Post' : 'Publish Post' }}</PrimaryButton>
                        <SecondaryButton v-if="isEditingBlog" @click="isEditingBlog = false; blogForm.reset()" class="ml-2">Cancel</SecondaryButton>
                    </form>

                    <div class="space-y-3">
                        <div v-for="post in $page.props.posts" :key="post.id" class="flex justify-between items-center bg-gray-900 p-4 rounded-lg border border-gray-700">
                            <span class="text-white font-medium">{{ post.title }}</span>
                            <div class="flex gap-4">
                                <button @click="editBlog(post)" class="text-indigo-400 text-sm">Edit</button>
                                <button @click="blogForm.delete(route('blog.destroy', post.id))" class="text-red-500 text-sm">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 border border-gray-700 shadow-2xl rounded-2xl p-8">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-white">Project Management</h2>
                            <p class="text-gray-400 text-sm">Daftar portofolio utama kamu.</p>
                        </div>
                        <PrimaryButton @click="openCreateModal" class="bg-indigo-600">+ Tambah Proyek</PrimaryButton>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-700/50 text-gray-400 uppercase text-xs">
                                <tr>
                                    <th class="py-4 px-6">Judul</th>
                                    <th class="py-4 px-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700 text-white">
                                <tr v-for="project in projects" :key="project.id" class="hover:bg-gray-700/30 transition">
                                    <td class="py-4 px-6 font-medium">{{ project.title }}</td>
                                    <td class="py-4 px-6 text-right space-x-3">
                                        <button @click="openEditModal(project)" class="text-indigo-400 hover:text-indigo-300 font-bold">Edit</button>
                                        <button @click="deleteProject(project.id)" class="text-red-500 hover:text-red-400 font-bold">Hapus</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="bg-gray-800 border border-gray-700 shadow-2xl rounded-2xl p-8">
                        <h2 class="text-xl font-bold text-white mb-6">Tech Stack</h2>
                        <form @submit.prevent="addStack" class="flex flex-col gap-3 mb-6">
                            <div class="flex gap-2">
                                <TextInput 
                                    v-model="stackForm.name" 
                                    placeholder="Nama (e.g. Laravel)" 
                                    class="w-1/3 bg-gray-900 border-gray-700 text-white" 
                                    required 
                                />
                                <TextInput 
                                    v-model="stackForm.url" 
                                    placeholder="URL (e.g. https://laravel.com)" 
                                    class="flex-1 bg-gray-900 border-gray-700 text-white" 
                                    required 
                                />
                            </div>
                            <PrimaryButton :disabled="stackForm.processing" class="justify-center">
                                Tambah Tech Stack
                            </PrimaryButton>
                        </form>
                        <div class="flex flex-wrap gap-2">
                            <div v-for="stack in stacks" :key="stack.id" class="group flex items-center gap-2 bg-gray-700/50 px-3 py-1 rounded-full border border-white/10">
                                <span class="text-sm text-gray-200">{{ stack.name }}</span>
                                <button @click="deleteStack(stack.id)" class="text-gray-500 hover:text-red-500 font-bold text-xs">✕</button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 border border-gray-700 shadow-2xl rounded-2xl p-8">
                        <h2 class="text-xl font-bold text-white mb-6">Social Media Links</h2>
                        <form @submit.prevent="addSocial" class="space-y-3 mb-6">
                            <div class="flex gap-2">
                                <TextInput v-model="socialForm.key" placeholder="Platform (e.g. Instagram)" class="w-1/3 bg-gray-900 border-gray-700 text-white" required />
                                <TextInput v-model="socialForm.value" placeholder="URL (https://...)" class="flex-1 bg-gray-900 border-gray-700 text-white" required />
                            </div>
                            <PrimaryButton :disabled="socialForm.processing" class="w-full justify-center">Simpan Link</PrimaryButton>
                        </form>
                        <div class="space-y-2">
                            <div v-for="social in socials" :key="social.id" class="flex justify-between items-center bg-gray-900 p-3 rounded-lg border border-gray-700">
                                <div>
                                    <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest">{{ social.key }}</p>
                                    <p class="text-[10px] text-gray-500 truncate max-w-[150px]">{{ social.value }}</p>
                                </div>
                                <button @click="deleteSocial(social.id)" class="text-red-500 hover:bg-red-500/10 p-2 rounded-md transition">Hapus</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <Modal :show="showingModal" @close="showingModal = false">
            <form @submit.prevent="submit" class="p-8 bg-gray-800 text-white border border-gray-700">
                <h2 class="text-xl font-bold mb-6">{{ isEditing ? 'Edit Proyek' : 'Tambah Proyek Baru' }}</h2>
                <div class="space-y-4">
                    <div>
                        <InputLabel value="Judul Proyek" class="text-gray-300" />
                        <TextInput v-model="form.title" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white" required />
                    </div>
                    <div>
                        <InputLabel value="Deskripsi" class="text-gray-300" />
                        <textarea v-model="form.description" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white rounded-md shadow-sm" rows="3" required></textarea>
                    </div>
                    <div>
                        <InputLabel value="Tech Stack (Koma)" class="text-gray-300" />
                        <TextInput v-model="form.tech_stack" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white" required />
                    </div>
                    <div>
                        <InputLabel value="Preview Image" class="text-gray-300" />
                        <input type="file" @input="form.image = $event.target.files[0]" class="mt-2 text-sm text-gray-400" />
                    </div>
                    <div>
                        <InputLabel value="Link Project" class="text-gray-300" />
                        <TextInput v-model="form.link" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white" />
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <SecondaryButton @click="showingModal = false" class="mr-3">Batal</SecondaryButton>
                    <PrimaryButton :disabled="form.processing">{{ isEditing ? 'Update' : 'Simpan' }}</PrimaryButton>
                </div>
            </form>
        </Modal>
    </AuthenticatedLayout>
</template>

<style>
.custom-quill .ql-editor img {
    width: auto;
    max-width: 100%;
    max-height: 500px;
    object-fit: contain;
    display: block;
    margin: 20px auto;
    border-radius: 14px;
}

.custom-quill .ql-editor {
    min-height: 300px;
}

.custom-quill .ql-editor p {
    margin-bottom: 12px;
}

/* HEADING */
.custom-quill .ql-editor h1 {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 16px;
    line-height: 1.2;
}

.custom-quill .ql-editor h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 14px;
    line-height: 1.3;
}

.custom-quill .ql-editor h3 {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 12px;
    line-height: 1.4;
}
</style>
