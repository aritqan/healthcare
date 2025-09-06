<?php

return [
    'notifications'                                             => 'الإشعارات',
    'mark_all_as_read'                                          => 'وضع علامة على الكل كمقروء',
    'notification_templates'                                    => [
        'welcome_in_our_platform'                               => [
            'title'                                             => 'مرحباً بك في منصة NABD',
            'description'                                       => 'هذه رسالة ترحيب للمستخدم',
            'short_template'                                    => '
                    <p>عزيزي/عزيزتي <strong>{{username}}</strong>,</p>

                    <p>نرحب بك في <strong>منصة NABD</strong> ويسعدنا انضمامك إلينا.</p>

                    <p>تم إنشاء حسابك بنجاح، وفيما يلي بيانات الدخول الخاصة بك:</p>

                    <ul>
                        <li><strong>اسم المستخدم:</strong> {{email}}</li>
                        <li><strong>كلمة المرور المؤقتة:</strong> {{password}}</li>
                    </ul>

                    <p>يمكنك تسجيل الدخول عبر الرابط التالي:</p>
                    <p><a href="{{loginUrl}}">{{loginUrl}}</a></p>

                    <p><strong>يرجى تغيير كلمة المرور عند تسجيل الدخول لأول مرة لضمان أمان حسابك.</strong></p>

                    <p>مع أطيب التحيات,<br>فريق NABD</p>
            ',
            'long_template'                                    => '
                <div class="container">
                    <div class="header">
                        <h1>مرحباً بك في منصة NABD</h1>
                        <p>منصة متكاملة لتسهيل عملك وإدارة مشاريعك</p>
                    </div>

                    <div class="content">
                        <div class="welcome">
                            <p>عزيزي/عزيزتي <strong>{{username}}</strong>,</p>
                            <p>نرحب بك في <strong>منصة NABD</strong> ويسعدنا انضمامك إلينا. تم إنشاء حسابك بنجاح، وفيما يلي بيانات الدخول الخاصة بك:</p>
                        </div>

                        <div class="credentials">
                            <div class="credential-item">
                                <span class="credential-label">اسم المستخدم:</span>
                                <span class="credential-value">{{email}}</span>
                            </div>
                            <div class="credential-item">
                                <span class="credential-label">كلمة المرور المؤقتة:</span>
                                <span class="credential-value password">{{password}}</span>
                            </div>
                        </div>

                        <a href="{{loginUrl}}" class="login-button">تسجيل الدخول إلى حسابك</a>

                        <p style="text-align: center; margin-bottom: 30px;">
                            أو يمكنك نسخ الرابط التالي إلى متصفحك:<br>
                            <span style="color: #4a6fdc; word-break: break-all;">{{loginUrl}}</span>
                        </p>

                        <div class="note">
                            <h3>ملاحظة مهمة</h3>
                            <p>يرجى تغيير كلمة المرور عند تسجيل الدخول لأول مرة لضمان أمان حسابك.</p>
                        </div>

                        <div class="support">
                            <p>نتطلع إلى تزويدك بتجربة مميزة ومفيدة على منصتنا.</p>
                            <p>إذا كان لديك أي استفسار، لا تتردد في التواصل مع فريق الدعم.</p>
                        </div>
                    </div>
                </div>
            ',
        ],
        'priority'                                              => [
            'low'                                               => 'منخفض',
            'medium'                                            => 'متوسط',
            'high'                                              => 'عالي',
            'default'                                           => 'افتراضي',
        ],
    ],
    'statuses'                                                  => [
        'delivered'                                             => 'تم الإستلام',
        'pending'                                               => 'قيد الإنتظار',
        'seen'                                                  => 'تمت مشاهدته',
        'failed'                                                => 'فشل',
        'read'                                                  => 'تمت القراءة',
    ],
    'added_by'                                                  => [
        'system'                                                => 'النظام',
        'admin'                                                 => 'المشرف',
    ],
];
