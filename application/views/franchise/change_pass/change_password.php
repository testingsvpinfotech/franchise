<?php $this->load->view('franchise/franchise_shared/franchise_header'); ?>
<style>
    .mainDiv {
    display: flex;
    min-height: 100%;
    align-items: center;
    justify-content: center;
    background-color: #f9f9f9;
    font-family: 'Open Sans', sans-serif;
  }
 .cardStyle {
    width: 420px;
    border-color: white;
    background: #fff;
    padding: 36px 0;
    border-radius: 4px;
    margin: 30px 0;
    box-shadow: 0px 0 2px 0 rgba(0,0,0,0.25);
  }
#signupLogo {
  max-height: 100px;
  margin: auto;
  display: flex;
  flex-direction: column;
}
.formTitle{
  font-weight: 600;
  margin-top: 20px;
  color: #2F2D3B;
  text-align: center;
}
.inputLabel {
  font-size: 12px;
  color: #555;
  margin-bottom: 6px;
  margin-top: 24px;
}
  .inputDiv {
    width: 80%;
    display: flex;
    flex-direction: column;
    margin: auto;
  }
input {
  height: 40px;
  font-size: 16px;
  border-radius: 4px;
  border: none;
  border: solid 1px #ccc;
  padding: 0 11px;
}
input:disabled {
  cursor: not-allowed;
  border: solid 1px #eee;
}
.buttonWrapper {
  margin-top: 40px;
}
  .submitButton {
    width: 70%;
    height: 40px;
    margin: auto;
    display: block;
    color: #fff;
    background-color: #065492;
    border-color: #065492;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.12);
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.035);
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
  }
.submitButton:disabled,
button[disabled] {
  border: 1px solid #cccccc;
  background-color: #cccccc;
  color: #666666;
}

#loader {
  position: absolute;
  z-index: 1;
  margin: -2px 0 0 10px;
  border: 4px solid #f3f3f3;
  border-radius: 50%;
  border-top: 4px solid #666666;
  width: 14px;
  height: 14px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
<body>
    <?php $this->load->view('franchise/franchise_shared/franchise_sidebar');?>    
    <main>
    <div class="mainDiv">
    
        <div class="cardStyle">
        <?php if ($this->session->flashdata('notify') != '') { ?>
                                            <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
                                        <?php unset($_SESSION['class']);
                                        unset($_SESSION['notify']);
                                    } ?>
            <form action="<?php echo base_url('franchise/change_password');?>" method="post" id="signupForm">
           
                <div class="inputDiv">
                    <label class="inputLabel" for="password">Old Password</label>
                    <input type="password" id="oldPassword" name="oldPassword" required>
                    <input type="hidden" name="customer_id" value="<?php echo $_SESSION['customer_id'];?>">
                </div>
                <div class="inputDiv">
                    <label class="inputLabel" for="newPassword">New Password</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                </div>
                <div class="inputDiv">
                    <label class="inputLabel" for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <div class="inputDiv form-check">
                    <label class="form-check-labeln mt-3" for="showPassword">Show Password</label>
                    <input type="checkbox"  class="form-check-input" id="showPassword" onclick="togglePasswordVisibility()">
                </div>
                <div class="buttonWrapper">
                    <button type="submit" id="submitButton" class="submitButton pure-button pure-button-primary">
                        <span>Submit</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

    <?php $this->load->view('franchise/franchise_shared/franchise_footer');?>    
</body>