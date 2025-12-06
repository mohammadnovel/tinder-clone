<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Popular Users Alert</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #ff6b6b, #ee5a5a); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .user-card { background: #f9f9f9; padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid #ff6b6b; }
        .user-card .name { font-weight: bold; font-size: 16px; }
        .user-card .email { color: #666; font-size: 14px; }
        .likes-badge { display: inline-block; background: #ff6b6b; color: white; padding: 4px 12px; border-radius: 20px; font-size: 13px; margin-top: 8px; }
        .footer { text-align: center; padding: 20px; color: #999; font-size: 12px; background: #f9f9f9; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔥 Popular Users Alert</h1>
            <p><?php echo e($popularUsers->count()); ?> user(s) have reached 50+ likes!</p>
        </div>
        
        <div class="content">
            <p>Hello Admin,</p>
            <p>The following users have become popular on the platform:</p>

            <?php $__currentLoopData = $popularUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="user-card">
                <div class="name"><?php echo e($user->name); ?></div>
                <div class="email"><?php echo e($user->email); ?></div>
                <span class="likes-badge">❤️ <?php echo e($user->likes_count); ?> likes</span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <p style="margin-top: 20px;">You may want to review these profiles.</p>
        </div>
        
        <div class="footer">
            <p>Sent at: <?php echo e(now()->format('Y-m-d H:i:s')); ?></p>
            <p>This is an automated notification from Tinder Clone.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH /Users/mohammadnovel/Downloads/tinder-clone-complete/tinder-backend/resources/views/emails/popular-users-alert.blade.php ENDPATH**/ ?>