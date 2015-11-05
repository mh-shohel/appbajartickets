<?php

namespace Mhshohel\Appbajarticket\Seeds;

use Illuminate\Database\Seeder;
use Mhshohel\Appbajarticket\Models\Setting;

class SettingsTableSeeder extends Seeder
{

    public $config = [];

    /**
     * Seed the Plans table
     */
    public function run()
    {

        $defaults = [];

        $defaults = $this->cleanupAndMerge($this->getDefaults(), $this->config);

        foreach ($defaults as $slug => $column) {

            $setting = Setting::bySlug($slug);

            if ($setting->count()) {
                $setting->first()->update([
                    'default' => $column,
                ]);
            } else {
                Setting::create([
                    'lang' => null,
                    'slug' => $slug,
                    'value' => $column,
                    'default' => $column,
                ]);
            }

        }

    }

    /**
     * Takes config/ticketit.php, merge with package defaults, and returns serialized array.
     *
     * @param $defaults
     * @param $config
     * @return array
     */
    public function cleanupAndMerge($defaults, $config)
    {

        $merged = array_merge($defaults, $config);

        foreach ($merged as $slug => $column) {

            if (is_array($column)) {

                foreach ($column as $key => $value) {

                    if ($value == "yes") {
                        $merged[$slug][$key] = true;
                    }

                    if ($value == "no") {
                        $merged[$slug][$key] = false;
                    }

                }

                $merged[$slug] = serialize($merged[$slug]);
            }

            if ($column == 'yes') {
                $merged[$slug] = true;
            }

            if ($column == 'no') {
                $merged[$slug] = false;
            }
        }

        return (array) $merged;
    }

    public function getDefaults()
    {
        return [

            /*
             * Appbajarticket main route: Where to load the ticket system (ex. http://url/tickets)
             * Default: /ticket
             */
            'main_route' => 'tickets',
            /*
             * Appbajarticket admin route: Where to load the ticket administration dashboard (ex. http://url/tickets-admin)
             * Default: /ticket
             */
            'admin_route' => 'tickets-admin',
            /*
             * Template adherence: The master blade template to be extended
             * Default: resources/views/master.blade.php
             */
            'master_template' => 'master',
            /*
             * Template adherence: The email blade template to be extended
             * Default: ticketit::emails.templates.ticketit
             */
            'email.template' => 'ticketit::emails.templates.ticketit',
            // resources/views/emails/templates/ticketit.blade.php
            'email.header' => 'Ticket Update',
            'email.signoff' => 'Thank you for your patience!',
            'email.signature' => 'Your friends',
            'email.dashboard' => 'My Dashboard',
            'email.google_plus_link' => '#', // Toogle icon link: false or string
            'email.facebook_link' => '#', // Toogle icon link: false or string
            'email.twitter_link' => '#', // Toogle icon link: false or string
            'email.footer' => 'Powered by Appbajarticket',
            'email.footer_link' => 'https://github.com/mh-shohel/pppbajarticket',
            'email.color_body_bg' => '#FFFFFF',
            'email.color_header_bg' => '#44B7B7',
            'email.color_content_bg' => '#F46B45',
            'email.color_footer_bg' => '#414141',
            'email.color_button_bg' => '#AC4D2F',
            /*
             * The default status for new created tickets
             * Default: 1
             */
            'default_status_id' => 1,
            /*
             * The default closing status
             * Default: false
             */
            'default_close_status_id' => false,
            /*
             * The default reopening status
             * Default: false
             */
            'default_reopen_status_id' => false,
            /*
             * User ids who are members of admin role
             * Default: 1
             */
            'admin_ids' => [1],
            /*
             * Pagination length: For standard pagination.
             * Default: 1
             */
            'paginate_items' => 10,
            /*
             * Pagination length: For tickets table.
             * Default: 1
             */
            'length_menu' => [[10, 50, 100], [10, 50, 100]],
            /*
             * Status notification: send email notification to ticket owner/Agent when ticket status is changed
             * Default is send notification: 'yes'
             * Do not send notification: 'no'
             */
            'status_notification' => 'yes',
            /*
             * Comment notification: Send notification when new comment is posted
             * Default is send notification: 'yes'
             * Do not send notification: 'no'
             */
            'comment_notification' => 'yes',
            /*
             * Use Queue method when sending emails (Mail::queue instead of Mail::send). Note that Mail::queue needs to be
             * configured first http://laravel.com/docs/5.1/queues
             * Default is to not use queue: 'no'
             * use queue: 'yes'
             */
            'queue_emails' => 'no',
            /*
             * Agent notify: To notify assigned agent (either auto or manual assignment) of new assigned or transferred tickets
             * Default: 'yes'
             * not to notify agent: 'no'
             */
            'assigned_notification' => 'yes',
            /*
             * Agent restrict: Restrict agents access to only their assigned tickets
             * Default: 'no'
             * Agent access only assigned tickets: 'yes'
             */
            'agent_restrict' => 'no',
            /*
             * Close Ticket Perm: Whose has a permission to close tickets
             * Default: ['owner' => 'yes', 'agent' => 'yes', 'admin' => 'yes']
             */
            'close_ticket_perm' => ['owner' => 'yes', 'agent' => 'yes', 'admin' => 'yes'],
            /*
             * Reopen Ticket Perm: Whose has a permission to reopen tickets
             * Default: ['owner' => 'yes', 'agent' => 'yes', 'admin' => 'yes']
             */
            'reopen_ticket_perm' => ['owner' => 'yes', 'agent' => 'yes', 'admin' => 'yes'],
            /*
             * Delete Confirmation: Choose which confirmation message type to use when confirming a deleting
             * Default: builtin
             * Options: builtin, modal
             */
            'delete_modal_type' => 'builtin',
            'ticketCreate_notification' => 'yes',

        ];

    }
}
