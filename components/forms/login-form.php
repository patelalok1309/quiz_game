<div class="sign-in-form">
    <h2 class="text-center">Sign in</h2>
    <form action="controllers/AuthController.php" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Sign in</button>
    </form>
    <p class="mt-3 text-center">Don't have an account? <a href="register.php">Sign up</a></p>
</div>
