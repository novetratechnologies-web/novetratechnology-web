<template>
  <form class="profile-form-shell profile-security-form" @submit.prevent="checkForm">
    <div v-if="success" class="profile-feedback profile-feedback-success" role="status"><i class="fa-solid fa-circle-check"></i>{{ success }}</div>
    <div v-if="formError" class="profile-feedback profile-feedback-error" role="alert"><i class="fa-solid fa-circle-exclamation"></i>{{ formError }}</div>

    <div class="profile-security-intro"><span><i class="fa-solid fa-key"></i></span><div><h3>Choose a strong password</h3><p>Use at least 8 characters. A mix of words, numbers and symbols is harder to guess.</p></div></div>
    <div class="profile-password-fields">
      <label class="profile-field"><span>Current password</span><div class="profile-password-input"><input v-model="oldpassword" id="oldpassword" dusk="old-password-input" name="oldpassword" :type="showOld ? 'text' : 'password'" autocomplete="current-password" placeholder="Enter your current password"><button type="button" :aria-label="showOld ? 'Hide current password' : 'Show current password'" @click="showOld = !showOld"><i :class="showOld ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i></button></div><small v-if="errors.oldpassword">{{ firstError(errors.oldpassword) }}</small></label>
      <label class="profile-field"><span>New password</span><div class="profile-password-input"><input v-model="newpassword" id="newpassword" name="newpassword" dusk="new-password-input" :type="showNew ? 'text' : 'password'" autocomplete="new-password" placeholder="At least 8 characters"><button type="button" :aria-label="showNew ? 'Hide new password' : 'Show new password'" @click="showNew = !showNew"><i :class="showNew ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i></button></div><small v-if="errors.newpassword">{{ firstError(errors.newpassword) }}</small></label>
      <label class="profile-field"><span>Confirm new password</span><div class="profile-password-input"><input v-model="confirmpassword" id="confirmpassword" name="confirmpassword" dusk="confirm-password-input" :type="showConfirm ? 'text' : 'password'" autocomplete="new-password" placeholder="Re-enter your new password"><button type="button" :aria-label="showConfirm ? 'Hide confirmation' : 'Show confirmation'" @click="showConfirm = !showConfirm"><i :class="showConfirm ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i></button></div><small v-if="errors.confirmpassword">{{ firstError(errors.confirmpassword) }}</small></label>
    </div>
    <div class="profile-form-actions profile-security-actions"><span><i class="fa-solid fa-shield-halved"></i> You’ll stay signed in after changing your password.</span><button type="submit" id="submit" dusk="submit-button" class="profile-save-button" :disabled="saving"><i :class="saving ? 'fa-solid fa-spinner fa-spin' : 'fa-solid fa-lock'"></i>{{ saving ? 'Updating…' : 'Update password' }}</button></div>
  </form>
</template>

<script>
export default {
  props: ['url'],
  data() { return { oldpassword: '', newpassword: '', confirmpassword: '', errors: {}, success: null, formError: null, saving: false, showOld: false, showNew: false, showConfirm: false }; },
  methods: {
    async checkForm() {
      this.errors = {}; this.formError = null; this.success = null; this.saving = true;
      try {
        const response = await axios.post('/admin/changepassword', { oldpassword: this.oldpassword, newpassword: this.newpassword, confirmpassword: this.confirmpassword });
        this.success = response.data.message || 'Your password has been updated.'; this.resetForm();
      } catch (error) {
        this.errors = error.response && error.response.data && error.response.data.errors ? error.response.data.errors : {};
        this.formError = error.response && error.response.data && error.response.data.message && !Object.keys(this.errors).length ? error.response.data.message : null;
        if (!Object.keys(this.errors).length && !this.formError) this.formError = 'We could not update your password. Please try again.';
      } finally { this.saving = false; }
    },
    resetForm() { this.oldpassword = ''; this.newpassword = ''; this.confirmpassword = ''; },
    firstError(value) { return Array.isArray(value) ? value[0] : value; }
  }
};
</script>
