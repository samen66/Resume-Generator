<template>
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Edit Resume</h2>
    <form @submit.prevent="submitForm">
      <!-- Similar fields as CreateResume -->
      <div class="flex justify-end">
        <button
          type="button"
          class="bg-gray-500 text-white px-4 py-2 rounded mr-2"
          @click="$emit('close')"
        >
          Cancel
        </button>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
          Update
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import { ref, watch } from "vue";
import axios from "axios";

export default {
  name: "EditResume",
  props: {
    resume: {
      type: Object,
      required: true,
    },
  },
  setup(props, { emit }) {
    const form = ref({ ...props.resume });

    watch(
      () => props.resume,
      (newResume) => {
        form.value = { ...newResume };
      }
    );

    const submitForm = async () => {
      try {
        await axios.put(`/profile/resume/${form.value.id}`, form.value);
        alert("Resume updated successfully!");
        emit("close");
      } catch (error) {
        console.error(error);
        alert("An error occurred while updating the resume.");
      }
    };

    return {
      form,
      submitForm,
    };
  },
};
</script>
