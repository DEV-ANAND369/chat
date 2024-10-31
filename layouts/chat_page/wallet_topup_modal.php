<div class="modal fade wallet_topup_modal" id="walletTopUpModal" tabindex="-1" aria-labelledby="walletTopUpModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="walletTopUpModalLabel"><?php echo Registry::load('strings')->top_up_your_wallet ?></h5>
            </div>
            <div class="modal-body">
                <form class="walletTopUpForm no_form_submit">
                    <div class="mb-4">
                        <div class="error"></div>
                        <label for="amount" class="form-label">
                            <?php echo Registry::load('strings')->top_up_amount.' ('.Registry::load('settings')->default_currency_symbol.')'; ?> :
                        </label>
                        <input type="number" name="topup_amount" class="topup_amount" placeholder="<?php echo Registry::load('strings')->enter_amount ?>" required>
                        <input type="hidden" name="payment_method_id" class="payment_method_id_selected">
                    </div>
                    <div class="mb-4">
                        <label class="form-label mb-4"><?php echo Registry::load('strings')->select_payment_method ?> : </label>

                        <ul class="wallet_payment_gateways">

                            <?php
                            $columns = $join = $where = null;
                            $columns = ['payment_gateways.payment_gateway_id', 'payment_gateways.identifier'];
                            $where["payment_gateways.disabled[!]"] = 1;
                            $payment_gateways = DB::connect()->select('payment_gateways', $columns, $where);

                            foreach ($payment_gateways as $payment_gateway) {
                                $payment_gateway_id = $payment_gateway['payment_gateway_id'];
                                $color_scheme = 'light';

                                if (Registry::load('current_user')->color_scheme == 'dark_mode') {
                                    $color_scheme = 'dark';
                                }
                                $gateway_image = Registry::load('config')->site_url;
                                $gateway_image = $gateway_image.'assets/files/payment_gateways/'.$color_scheme.'/'.$payment_gateway['identifier'].'.png';
                                ?>
                                <li>
                                    <div class="payment_method" payment_gateway_id="<?php echo $payment_gateway_id; ?>">
                                        <img src="<?php echo $gateway_image; ?>">
                                    </div>
                                </li>
                                <?php
                            }
                            ?>

                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo Registry::load('strings')->close ?></button>
                <button type="button" class="btn btn-primary topup_wallet_submit"><?php echo Registry::load('strings')->top_up ?></button>
            </div>
        </div>
    </div>
</div>