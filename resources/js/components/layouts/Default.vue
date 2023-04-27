<template>
  <FooterHomeVue />
  <slot></slot>
</template>
<script>
import FooterHomeVue from "@/components/FooterHome.vue";
export default {
  components: {
    FooterHomeVue,
  },
};
</script>