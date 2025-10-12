<?php

namespace Modules\GlobalSetting\database\seeders;

use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'password_reset',
                'subject' => 'Password Reset',
                'message' => '<p>Dear {{user_name}},</p>
                <p>Do you want to reset your password? Please Click the following link and Reset Your Password.</p>',
            ],
            [
                'name' => 'contact_mail',
                'subject' => 'Contact Email',
                'message' => '<p>Hello there,</p>
                <p>&nbsp;Mr. {{name}} has sent a new message. you can see the message details below.&nbsp;</p>
                <p>Email: {{email}}</p>
                <p>Website: {{website}}</p>
                <p>Subject: {{subject}}</p>
                <p>Message: {{message}}</p>',
            ],
            [
                'name' => 'contact_team_mail',
                'subject' => 'Contact Email',
                'message' => '<p>Hello there,</p>
                <p>&nbsp;Mr. {{name}} has sent a new message. you can see the message details below.&nbsp;</p>
                <p>Email: {{email}}</p>
                <p>Message: {{message}}</p>',
            ],
            [
                'name' => 'subscribe_notification',
                'subject' => 'Subscribe Notification',
                'message' => '<p>Hi there, Congratulations! Your Subscription has been created successfully. Please Click the following link and Verified Your Subscription. If you will not approve this link, you can not get any newsletter from us.</p>',
            ],
            [
                'name' => 'social_login',
                'subject' => 'Social Login',
                'message' => '<p>Hello {{user_name}},</p>
                <p>Welcome to {{app_name}}! Your account has been created successfully.</p>
                <p>Your password: {{password}}</p>
                <p>You can log in to your account at <a href="https://websolutionus.com">https://websolutionus.com</a></p>
                <p>Thank you for joining us.</p>',
            ],

            [
                'name' => 'user_verification',
                'subject' => 'User Verification',
                'message' => '<p>Dear {{user_name}},</p>
                <p>Congratulations! Your account has been created successfully. Please click the following link to activate your account.</p>',
            ],
            [
                'name' => 'new_refund',
                'subject' => 'New Refund Request',
                'message' => '<p>Hello websolutionus, </p>

                <p>Mr. {{user_name}} has send a new refund request to you.</p>',
            ],
            [
                'name' => 'approved_refund',
                'subject' => 'Refund Request Approval',
                'message' => '<p>Dear {{user_name}},</p>
                <p>We are happy to say that, we have send {{refund_amount}} to your provided account information. </p>',
            ],
            [
                'name' => 'reject_refund',
                'subject' => 'Reject Refund Request',
                'message' => '<p>Dear {{user_name}},</p>
                <p>We regret to inform you that your refund request for order {{order_id}} has been declined.</p>',
            ],
            [
                'name' => 'blog_comment',
                'subject' => 'New Blog Comment',
                'message' => '<p>Hello {{admin_name}},</p>
                <p> A new pending comment has been added by <b>{{user_name}}</b> on <a href="{{link}}">{{blog_title}}</a></p>',
            ],
            [
                'name' => 'order_mail',
                'subject' => 'Order Confirmation Mail',
                'message' => '<p>Hi {{user_name}},</p><p>Thanks for your new order. Your order id has been submitted .</p><p><strong>Sub Total :</strong>  {{sub_total}},</p><p><strong>Discount :</strong>  {{discount}},</p><p><strong>Tax :</strong>  {{tax}},</p><p><strong>Delivery Charge :</strong>  {{delivery_charge}},</p><p><strong>Total Amount :</strong>  {{total_amount}},</p><p><strong>Payment Method :</strong> {{payment_method}},</p><p><strong>Payment Status :</strong> {{payment_status}},</p><p><strong>Order Status :</strong> {{order_status}},</p><p><strong>Order Date:</strong> {{order_date}},</p><div>{{order_detail}}</div>',
            ],
            [
                'name' => 'order_status',
                'subject' => 'Order Status Update',
                'message' => '<p>Dear {{user_name}},</p>
                <p>Your order #{{order_id}} is now <b>{{order_status}}</b>.</p>',
            ],
            [
                'name' => 'approved_payment',
                'subject' => 'Payment Approved',
                'message' => '<p>Dear {{user_name}},</p>
                <p>Your order #{{order_id}} payment is approved.</p></p>',
            ],
            [
                'name' => 'reject_payment',
                'subject' => 'Payment Reject',
                'message' => '<p>Dear {{user_name}},</p>
                <p>Your order #{{order_id}} payment is rejected.</p>',
            ],

        ];

        foreach ($templates as $index => $template) {
            $new_template = new EmailTemplate();
            $new_template->name = $template['name'];
            $new_template->subject = $template['subject'];
            $new_template->message = $template['message'];
            $new_template->save();
        }
    }
}
