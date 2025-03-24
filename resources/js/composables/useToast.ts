import { ref } from 'vue';

interface Toast {
  message: string;
  type: 'success' | 'error' | 'info' | 'warning';
  duration?: number;
}

const visible = ref(false);
const message = ref('');
const type = ref<Toast['type']>('info');

export function useToast() {
  const show = (toast: Toast) => {
    message.value = toast.message;
    type.value = toast.type;
    visible.value = true;

    setTimeout(() => {
      visible.value = false;
    }, toast.duration || 3000);
  };

  const success = (msg: string, duration?: number) => {
    show({ message: msg, type: 'success', duration });
  };

  const error = (msg: string, duration?: number) => {
    show({ message: msg, type: 'error', duration });
  };

  return {
    visible,
    message,
    type,
    toast: {
      success,
      error
    }
  };
}