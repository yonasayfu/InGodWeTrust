<template>
  <!-- Assuming this is part of your existing AppSidebar.vue -->
  <!-- Add this section where your navigation links are defined -->
  
  <!-- ... existing sidebar code ... -->
  
  <!-- Add this where your navigation links are -->
  <div class="space-y-1">
    <!-- ... other navigation links ... -->
    
    <Link
      :href="route('supporters.index')"
      :class="[
        route().current('supporters.*')
          ? 'bg-gray-100 text-gray-900'
          : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
        'group flex items-center px-2 py-2 text-sm font-medium rounded-md'
      ]"
    >
      <UserIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
      Supporters
    </Link>
    
    <Link
      :href="route('bank-forms.index')"
      :class="[
        route().current('bank-forms.*')
          ? 'bg-gray-100 text-gray-900'
          : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
        'group flex items-center px-2 py-2 text-sm font-medium rounded-md'
      ]"
    >
      <DocumentTextIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
      Bank Forms
    </Link>
    
    <!-- ... other navigation links ... -->
  </div>
  
  <!-- ... rest of sidebar code ... -->
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { UserIcon, DocumentTextIcon } from '@heroicons/vue/24/outline';
// ... other imports ...
</script>