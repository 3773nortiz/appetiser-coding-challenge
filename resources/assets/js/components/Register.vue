<template>
    <div class="container" id="register">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-info">
                    <div class="panel-heading">Register Here:</div>

                    <div class="panel-body">
                        <div class="alert" v-bind:class="[is_success ? successClass : errorClass]" role="alert" v-if="message">
                          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> 
                          {{message}}
                        </div>
                        <form name="register" @submit.prevent="register">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" v-model="user.name" class="form-control" value="" name="" placeholder="Name" 
                                required />
                            </div>
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" v-model="user.email" class="form-control" value="" name=""  placeholder="Email" 
                                required />
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" v-model="user.password" class="form-control" value="" name=""  placeholder="Password"required />
                            </div>
                            <button type="submit" class="btn btn-default" name="button">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  data: function () {
      const user = {
        name: '',
        email: '',
        password: '',
      };

      return {
          user,
          message: '',
          is_success: '',
          successClass: 'alert-success',
          errorClass: 'alert-danger'
      }
  },
  methods: {
    register: function () {
        const data = {
           name: this.user.name,
           email: this.user.email,
           password: this.user.password
        };
        this.submitRegistrationForm(data);
    },
    submitRegistrationForm: function (formData) {
      $.ajax({
           type: "POST",
           url: "/api/user/register",
           dataType: "json",
           data: formData,
           success: (response) => {
              if (!response) return;
              this.is_success = response.success;
              if (!this.is_success) {
                this.message = response.message;
              } else {
                this.message = 'Successfully Registered!';
                this.user = {};
              }

           }
       });
    }
  }
}
</script>

<style scoped>
 #register {
    margin-top: 10%;
 }
</style>