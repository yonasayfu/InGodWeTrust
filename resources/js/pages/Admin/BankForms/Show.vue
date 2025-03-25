<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Edit, ArrowLeft, Download, FileText } from 'lucide-vue-next';

interface BankForm {
  id: number;
  bank_name: string;
  form_name: string;
  form_file: string;
  form_file_url?: string;
  created_at: string;
  updated_at: string;
}

const props = defineProps<{
  bankForm: BankForm;
}>();

const downloadForm = () => {
  window.open(route('bank-forms.download', props.bankForm.id), '_blank');
};

const breadcrumbs = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Bank Forms', href: route('bank-forms.index') },
  { title: 'View Details', href: null },
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
          <Link :href="route('bank-forms.index')" class="text-gray-600 hover:text-gray-900">
            <ArrowLeft class="w-5 h-5" />
          </Link>
          <h1 class="text-3xl font-bold">Bank Form Details</h1>
        </div>
        <div class="flex items-center gap-3">
          <Button variant="outline" class="flex items-center gap-2" @click="downloadForm">
            <Download class="w-4 h-4" />
            <span>Download</span>
          </Button>
          <Link :href="route('bank-forms.edit', bankForm.id)">
            <Button variant="primary" class="flex items-center gap-2 bg-green-600 hover:bg-green-700">
              <Edit class="w-4 h-4" />
              <span>Edit</span>
            </Button>
          </Link>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Form header -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 border-b">
          <div class="flex items-center gap-6">
            <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
              <FileText class="w-8 h-8" />
            </div>
            <div>
              <h2 class="text-2xl font-bold text-gray-800">{{ bankForm.form_name }}</h2>
              <div class="mt-1 text-gray-500">
                From {{ bankForm.bank_name }}
              </div>
            </div>
          </div>
        </div>

        <!-- Form details -->
        <div class="p-6">
          <div class="bg-white rounded-lg border p-5 hover:shadow-md transition-shadow">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Form Information</h3>
            <div class="space-y-3">
              <div class="flex justify-between">
                <span class="text-gray-500">Bank Name:</span>
                <span class="font-medium">{{ bankForm.bank_name }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Form Name:</span>
                <span class="font-medium">{{ bankForm.form_name }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">File:</span>
                <span class="font-medium">{{ bankForm.form_file.split('/').pop() }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Created At:</span>
                <span class="font-medium">{{ new Date(bankForm.created_at).toLocaleString() }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Updated At:</span>
                <span class="font-medium">{{ new Date(bankForm.updated_at).toLocaleString() }}</span>
              </div>
            </div>
          </div>
          
          <div class="mt-6 flex justify-center">
            <Button 
              variant="primary" 
              class="flex items-center gap-2 bg-green-600 hover:bg-green-700"
              @click="downloadForm"
            >
              <Download class="w-5 h-5" />
              <span>Download Form Document</span>
            </Button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
  