<script setup lang="ts">
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { ArrowLeft, Save, Upload } from 'lucide-vue-next';

const form = useForm({
  bank_name: '',
  form_name: '',
  form_file: null as File | null,
});

const fileInputRef = ref<HTMLInputElement | null>(null);
const selectedFileName = ref<string | null>(null);

const handleFileChange = (event: Event) => {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files.length > 0) {
    form.form_file = input.files[0];
    selectedFileName.value = input.files[0].name;
  }
};

const submit = () => {
  form.post(route('bank-forms.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      selectedFileName.value = null;
    },
  });
};

const breadcrumbs = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Bank Forms', href: route('bank-forms.index') },
  { title: 'Create', href: null },
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <div class="flex items-center gap-4 mb-6">
        <Link :href="route('bank-forms.index')" class="text-gray-600 hover:text-gray-900">
          <ArrowLeft class="w-5 h-5" />
        </Link>
        <h1 class="text-3xl font-bold">Create Bank Form</h1>
      </div>

      <div class="bg-white rounded-lg shadow overflow-hidden">
        <form @submit.prevent="submit" class="p-6 space-y-6">
          <!-- Bank Name -->
          <div>
            <label for="bank_name" class="block text-sm font-medium text-blue-600">
              Bank Name <span class="text-red-500">*</span>
            </label>
            <input
              id="bank_name"
              v-model="form.bank_name"
              type="text"
              class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-bold"
              required
            />
            <div v-if="form.errors.bank_name" class="text-red-500 text-sm mt-1">{{ form.errors.bank_name }}</div>
          </div>

          <!-- Form Name -->
          <div>
            <label for="form_name" class="block text-sm font-medium text-blue-600">
              Form Name <span class="text-red-500">*</span>
            </label>
            <input
              id="form_name"
              v-model="form.form_name"
              type="text"
              class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-bold"
              required
            />
            <div v-if="form.errors.form_name" class="text-red-500 text-sm mt-1">{{ form.errors.form_name }}</div>
          </div>

          <!-- Form File -->
          <div>
            <label for="form_file" class="block text-sm font-medium text-blue-600">
              Form File <span class="text-red-500">*</span>
            </label>
            <div class="mt-1 flex items-center">
              <input
                ref="fileInputRef"
                type="file"
                class="hidden"
                @change="handleFileChange"
                accept=".pdf,.doc,.docx"
              />
              <Button 
                type="button" 
                variant="outline" 
                @click="fileInputRef?.click()"
                class="flex items-center gap-2"
              >
                <Upload class="w-4 h-4" />
                <span>Select File</span>
              </Button>
              <span v-if="selectedFileName" class="ml-3 text-sm text-gray-600">{{ selectedFileName }}</span>
              <span v-else class="ml-3 text-sm text-gray-500">No file selected</span>
            </div>
            <p class="mt-1 text-sm text-gray-500">Accepted formats: PDF, DOC, DOCX (max 10MB)</p>
            <div v-if="form.errors.form_file" class="text-red-500 text-sm mt-1">{{ form.errors.form_file }}</div>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end">
            <Button
              type="submit"
              variant="primary"
              class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600"
              :disabled="form.processing"
            >
              <Save class="w-4 h-4" />
              <span>Save Bank Form</span>
            </Button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
  