<template>
  <div class="profile-form-shell profile-avatar-settings">
    <div v-if="success" class="profile-feedback profile-feedback-success" role="status"><i class="fa-solid fa-circle-check"></i>{{ success }}</div>
    <div v-if="formError" class="profile-feedback profile-feedback-error" role="alert"><i class="fa-solid fa-circle-exclamation"></i>{{ formError }}</div>

    <div class="profile-photo-layout">
      <div class="profile-photo-preview-wrap">
        <img :src="preview || avatar || fallbackAvatar" class="profile-photo-preview" alt="Profile photo preview" @error="useFallback">
        <span class="profile-photo-edit-mark"><i class="fa-solid fa-camera"></i></span>
      </div>
      <div class="profile-photo-copy">
        <h3>Make it easy to recognize you</h3>
        <p>Upload a recent, well-lit photo. Use a JPG, PNG or WebP image up to 5 MB.</p>
        <label class="profile-upload-button" for="admin-profile-avatar"><i class="fa-solid fa-arrow-up-from-bracket"></i> Choose a photo</label>
        <input id="admin-profile-avatar" ref="fileInput" class="profile-file-input" type="file" accept="image/jpeg,image/png,image/webp" @change="onFileSelected">
        <small v-if="selectedFile" class="profile-selected-file"><i class="fa-regular fa-image"></i> {{ selectedFile.name }} · {{ formatSize(selectedFile.size) }}</small>
        <small v-if="errors.avatar" class="profile-field-error">{{ firstError(errors.avatar) }}</small>
      </div>
    </div>

    <div class="profile-form-actions profile-photo-actions">
      <span><i class="fa-solid fa-circle-info"></i> Your photo updates across the admin panel after it is saved.</span>
      <button type="button" class="profile-save-button" :disabled="!selectedFile || uploading" @click="uploadImage"><i :class="uploading ? 'fa-solid fa-spinner fa-spin' : 'fa-solid fa-check'"></i>{{ uploading ? 'Uploading…' : 'Save photo' }}</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: ['url'],
  data() {
    return { avatar: '', preview: '', selectedFile: null, errors: {}, success: null, formError: null, uploading: false, fallbackAvatar: (this.url || '') + '/uploads/user/avatar/default-user.jpg' };
  },
  methods: {
    async getData() {
      try { const response = await axios.get('/admin/getavatar'); this.avatar = response.data.avatar || this.fallbackAvatar; }
      catch (error) { this.avatar = this.fallbackAvatar; this.formError = 'We could not load your current photo. You can still choose and save a new one.'; }
    },
    onFileSelected(event) {
      this.errors = {}; this.success = null; this.formError = null;
      const file = event.target.files && event.target.files[0];
      this.selectedFile = null; this.preview = '';
      if (!file) return;
      const allowed = ['image/jpeg', 'image/png', 'image/webp'];
      if (!allowed.includes(file.type)) { this.errors = { avatar: ['Choose a JPG, PNG or WebP image.'] }; event.target.value = ''; return; }
      if (file.size > 5 * 1024 * 1024) { this.errors = { avatar: ['The image must be 5 MB or smaller.'] }; event.target.value = ''; return; }
      this.selectedFile = file;
      const reader = new FileReader();
      reader.onload = result => { this.preview = result.target.result; };
      reader.readAsDataURL(file);
    },
    async uploadImage() {
      if (!this.selectedFile || this.uploading) return;
      this.errors = {}; this.formError = null; this.success = null; this.uploading = true;
      const data = new FormData(); data.append('avatar', this.selectedFile);
      try {
        const response = await axios.post('/admin/changeavatar', data);
        this.avatar = response.data.avatar || this.preview; this.preview = ''; this.selectedFile = null;
        if (this.$refs.fileInput) this.$refs.fileInput.value = '';
        this.success = response.data.message || 'Your profile photo has been updated.';
        window.dispatchEvent(new CustomEvent('admin-profile-photo-updated', { detail: { avatar: this.avatar } }));
      } catch (error) {
        this.errors = error.response && error.response.data && error.response.data.errors ? error.response.data.errors : {};
        this.formError = error.response && error.response.data && error.response.data.message ? error.response.data.message : 'The photo could not be saved. Please try again.';
      } finally { this.uploading = false; }
    },
    useFallback(event) { if (event.target.src !== this.fallbackAvatar) event.target.src = this.fallbackAvatar; },
    firstError(value) { return Array.isArray(value) ? value[0] : value; },
    formatSize(bytes) { return bytes < 1024 * 1024 ? Math.max(1, Math.round(bytes / 1024)) + ' KB' : (bytes / (1024 * 1024)).toFixed(1) + ' MB'; }
  },
  created() { this.getData(); }
};
</script>
