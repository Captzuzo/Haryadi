:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --secondary: #f8fafc;
    --text: #1e293b;
    --text-light: #64748b;
    --border: #e2e8f0;
    --bg-primary: #ffffff;
    --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

[data-theme="dark"] {
    --primary: #818cf8;
    --primary-dark: #6366f1;
    --secondary: #1e293b;
    --text: #f1f5f9;
    --text-light: #94a3b8;
    --border: #334155;
    --bg-primary: #0f172a;
    --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
    --shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--bg-gradient);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text);
    line-height: 1.6;
    transition: all 0.3s ease;
    padding: 1rem;
}

.error-container {
    background: var(--bg-primary);
    padding: 3rem 2rem;
    border-radius: 20px;
    box-shadow: var(--shadow);
    text-align: center;
    max-width: 500px;
    width: 100%;
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.8s ease-out;
}

.error-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--primary-dark));
}

.error-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    animation: bounce 2s infinite;
}

.error-code {
    font-size: 5rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.error-title {
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--text);
}

.error-message {
    color: var(--text-light);
    margin-bottom: 2.5rem;
    font-size: 1.1rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.error-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 2rem;
}

.btn {
    padding: 0.875rem 1.75rem;
    border: none;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn-primary {
    background: var(--primary);
    color: white;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
}

.btn-secondary {
    background: var(--secondary);
    color: var(--text);
    border: 1px solid var(--border);
}

.btn-secondary:hover {
    background: var(--border);
    transform: translateY(-2px);
}

.error-details {
    background: var(--secondary);
    padding: 1.25rem;
    border-radius: 12px;
    font-size: 0.875rem;
    color: var(--text-light);
    text-align: left;
    border: 1px solid var(--border);
    margin-top: 2rem;
}

.error-details summary {
    font-weight: 600;
    cursor: pointer;
    margin-bottom: 0.5rem;
    color: var(--text);
}

.error-details div {
    margin-top: 0.75rem;
}

.error-details code {
    background: var(--bg-primary);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-family: 'Monaco', 'Menlo', monospace;
    font-size: 0.8rem;
    border: 1px solid var(--border);
}

.theme-toggle {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: var(--secondary);
    border: 1px solid var(--border);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: var(--text);
}

.theme-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

@media (max-width: 480px) {
    .error-container {
        padding: 2rem 1.5rem;
    }
    
    .error-code {
        font-size: 4rem;
    }
    
    .error-title {
        font-size: 1.5rem;
    }
    
    .error-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}

.btn.loading {
    pointer-events: none;
    opacity: 0.7;
}

.btn.loading::after {
    content: '';
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-left: 8px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}