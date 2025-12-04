# Agentic Resume-to-Role Classifier

A Laravel + AI application built for the AI-DevOps Hackathon. This tool accepts a resume (PDF/TXT), uses a local Python AI agent to analyze the candidate's skills, and assigns them a specific SAP role (SD, MM, FICO, etc.) along with learning tips.

## Deliverables Included
1. **Laravel API**: Endpoints for uploading and matching resumes.
2. **AI Integration**: Python script invoked by Laravel to perform logic.
3. **DevOps**: Custom Dockerfile and Kubernetes manifest.

## How to Run (Docker)

1. **Clone and Setup:**
   ```bash
   git clone <repo_url>
   cd <repo_directory>
   cp .env.example .env
   # Ensure your .env has DB credentials (DB_HOST=mysql)

2. **Start Application:**
    ```bash
    docker-compose up -d --build

3. **Initialize Application(if first time):**
    ```bash
    docker-compose exec app php artisan key:generate
    docker-compose exec app php artisan migrate --force

4. **Access: Open http://localhost:8000**

## How to Run (Kubernetes)

1. **Build Image:**
   ```bash
   docker build -t resume-analyzer-app .

2. **Deploy:**
   ```bash
   kubectl apply -f k8s.yaml

3. **Access: Open http://localhost:30007**