<template>
  <div class="profile-form-shell">
    <div v-if="success" class="profile-feedback profile-feedback-success" role="status"><i class="fa-solid fa-circle-check"></i>{{ success }}</div>
    <div v-if="formError" class="profile-feedback profile-feedback-error" role="alert"><i class="fa-solid fa-circle-exclamation"></i>{{ formError }}</div>

    <div class="profile-form-section">
      <div class="profile-form-section-title"><span>01</span><div><h3>Names</h3><p>How your name appears in church records.</p></div></div>
      <div class="profile-form-grid">
        <label class="profile-field"><span>First name</span><input v-model="firstname" type="text" autocomplete="given-name" placeholder="Your first name"><small v-if="errors.firstname">{{ errors.firstname[0] }}</small></label>
        <label class="profile-field"><span>Last name</span><input v-model="lastname" type="text" autocomplete="family-name" placeholder="Your last name"><small v-if="errors.lastname">{{ errors.lastname[0] }}</small></label>
        <label class="profile-field"><span>Birth first name <em>Optional</em></span><input v-model="birth_firstname" type="text" placeholder="Birth first name"><small v-if="errors.birth_firstname">{{ errors.birth_firstname[0] }}</small></label>
        <label class="profile-field"><span>Birth last name <em>Optional</em></span><input v-model="birth_lastname" type="text" placeholder="Birth last name"><small v-if="errors.birth_lastname">{{ errors.birth_lastname[0] }}</small></label>
      </div>
    </div>

    <div class="profile-form-section">
      <div class="profile-form-section-title"><span>02</span><div><h3>Personal information</h3><p>These details help keep your church records complete.</p></div></div>
      <div class="profile-form-grid">
        <label class="profile-field"><span>Date of birth</span><input v-model="date_of_birth" type="date" autocomplete="bday"><small v-if="errors.date_of_birth">{{ errors.date_of_birth[0] }}</small></label>
        <fieldset class="profile-field profile-gender-field"><legend>Gender</legend><div class="profile-choice-row"><label><input v-model="gender" type="radio" value="male"><span>Male</span></label><label><input v-model="gender" type="radio" value="female"><span>Female</span></label></div><small v-if="errors.gender">{{ errors.gender[0] }}</small></fieldset>
      </div>
    </div>

    <div class="profile-form-section">
      <div class="profile-form-section-title"><span>03</span><div><h3>Location</h3><p>Your location information for church administration.</p></div></div>
      <p v-if="locationError" class="profile-location-error" role="alert"><i class="fa-solid fa-circle-exclamation"></i>{{ locationError }}</p>
      <div class="profile-form-grid">
        <label class="profile-field profile-field-wide"><span>Address</span><input v-model="address" type="text" autocomplete="street-address" placeholder="Street, estate or area"><small v-if="errors.address">{{ errors.address[0] }}</small></label>
        <label class="profile-field"><span>Country</span><select v-model="country_id" @change="onCountryChange"><option disabled value="">Choose a country</option><option v-for="country in countrylist" :key="country.id" :value="country.id">{{ country.name }}</option></select><small v-if="errors.country_id">{{ errors.country_id[0] }}</small></label>
        <label class="profile-field"><span>State / region</span><select v-model="state_id" :disabled="!country_id || loadingStates" @change="onStateChange"><option disabled value="">{{ loadingStates ? 'Loading regions…' : 'Choose a region' }}</option><option v-for="state in (statelist[country_id] || [])" :key="state.id" :value="state.id">{{ state.name }}</option></select><small v-if="errors.state_id">{{ errors.state_id[0] }}</small></label>
        <label class="profile-field"><span>City / town</span><select v-model="city_id" :disabled="!state_id || loadingCities"><option disabled value="">{{ loadingCities ? 'Loading cities…' : 'Choose a city' }}</option><option v-for="city in (citylist[state_id] || [])" :key="city.id" :value="city.id">{{ city.name }}</option></select><small v-if="errors.city_id">{{ errors.city_id[0] }}</small></label>
        <label class="profile-field"><span>Postal code</span><input v-model="pincode" type="text" autocomplete="postal-code" placeholder="Postal code"><small v-if="errors.pincode">{{ errors.pincode[0] }}</small></label>
      </div>
    </div>

    <div class="profile-form-actions"><span><i class="fa-solid fa-shield-halved"></i> Your information is stored securely.</span><button type="button" class="profile-save-button" :disabled="saving" @click="checkForm"><i :class="saving ? 'fa-solid fa-spinner fa-spin' : 'fa-solid fa-check'"></i>{{ saving ? 'Saving…' : 'Save changes' }}</button></div>
  </div>
</template>

<script>
export default {
  props: ['url'],
  data() {
    return { user: {}, firstname: '', lastname: '', birth_firstname: '', birth_lastname: '', gender: '', date_of_birth: '', address: '', city_id: '', state_id: '', country_id: '', pincode: '', countrylist: [], statelist: {}, citylist: {}, errors: {}, success: null, formError: null, locationError: null, loadingStates: false, loadingCities: false, saving: false };
  },
  methods: {
    async checkForm() {
      this.errors = {}; this.success = null; this.formError = null; this.saving = true;
      const payload = { firstname: this.firstname, lastname: this.lastname, birth_firstname: this.birth_firstname, birth_lastname: this.birth_lastname, gender: this.gender, date_of_birth: this.date_of_birth, address: this.address, city_id: this.city_id, state_id: this.state_id, country_id: this.country_id, pincode: this.pincode };
      try { const response = await axios.post('/admin/profile', payload); this.success = response.data.message || 'Your profile has been updated.'; }
      catch (error) { this.errors = error.response && error.response.data && error.response.data.errors ? error.response.data.errors : {}; this.formError = error.response && error.response.data && error.response.data.message ? error.response.data.message : 'We could not save your profile. Please check your connection and try again.'; }
      finally { this.saving = false; }
    },
    async getData() {
      try { const response = await axios.get('/admin/profile'); this.user = response.data || {}; this.setData(); }
      catch (error) { this.formError = 'We could not load your profile details. Refresh the page to try again.'; }
    },
    async onCountryChange() {
      this.state_id = ''; this.city_id = ''; this.statelist = {}; this.citylist = {}; this.locationError = null;
      if (!this.country_id) return;
      this.loadingStates = true;
      try { const response = await axios.get('/admin/profile/states/' + encodeURIComponent(this.country_id)); this.$set(this.statelist, this.country_id, response.data || []); }
      catch (error) { this.locationError = 'Could not load regions for this country. Please try again.'; }
      finally { this.loadingStates = false; }
    },
    async onStateChange() {
      this.city_id = ''; this.citylist = {}; this.locationError = null;
      if (!this.state_id) return;
      this.loadingCities = true;
      try { const response = await axios.get('/admin/profile/cities/' + encodeURIComponent(this.state_id)); this.$set(this.citylist, this.state_id, response.data || []); }
      catch (error) { this.locationError = 'Could not load cities for this region. Please try again.'; }
      finally { this.loadingCities = false; }
    },
    setData() {
      this.firstname = this.user.firstname || ''; this.lastname = this.user.lastname || ''; this.birth_firstname = this.user.birth_firstname || ''; this.birth_lastname = this.user.birth_lastname || ''; this.gender = this.user.gender || ''; this.date_of_birth = this.user.date_of_birth || ''; this.address = this.user.address || ''; this.country_id = this.user.country_id || ''; this.state_id = this.user.state_id || ''; this.city_id = this.user.city_id || ''; this.countrylist = this.user.countrylist || []; this.statelist = this.user.statelist || {}; this.citylist = this.user.citylist || {}; this.pincode = this.user.pincode || '';
    }
  },
  created() { this.getData(); }
};
</script>
