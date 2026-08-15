# LifeLink — Updated System Diagrams

These reflect the **actual implemented system**, superseding the proposal's pre-implementation (conceptual) diagrams. Use these for the thesis's System Design chapter.

---

## Figure A — Entity-Relationship Diagram

```mermaid
erDiagram
    DONORS ||--o{ DONATIONS : "has many"
    HOSPITALS ||--o{ BLOOD_REQUESTS : "submits"

    DONORS {
        bigint id PK
        string first_name
        string last_name
        string email UK
        string password
        string phone
        date date_of_birth
        int age
        string gender
        string nic UK
        string blood_group
        decimal weight_kg
        decimal hemoglobin
        int total_donations
        date last_donation_date
        string city
        string district
        string donation_center
        string profile_image
        string medical_condition
        text medical_notes
        boolean is_eligible
        decimal ai_confidence
        decimal response_probability
        string response_level
        boolean is_anomaly
        decimal anomaly_score
        timestamp last_ai_check
    }

    HOSPITALS {
        bigint id PK
        string name
        string email UK
        string password
        string registration_id UK
        string phone
        string city
        string district
        string address
        boolean is_verified
    }

    ADMINS {
        bigint id PK
        string name
        string email UK
        string password
        string role
    }

    BLOOD_REQUESTS {
        bigint id PK
        bigint hospital_id FK
        string blood_group
        int units_needed
        enum urgency "standard|urgent|critical"
        string ward
        date required_by
        text notes
        enum status "pending|fulfilled|cancelled"
    }

    DONATIONS {
        bigint id PK
        bigint donor_id FK
        date donation_date
        string blood_group
        string donation_center
        int units
        text notes
    }
```

**Note vs. the proposal's ER diagram:** the proposal's conceptual diagram included an `AI_PREDICTION` table linking donors and requests. In the implemented system, matching is computed **at query time** (an exact blood-group filter, sorted by `ai_confidence`) rather than persisted as a stored prediction record — there is no `AI_PREDICTION` table. This is a deliberate implementation simplification worth discussing in the System Design chapter: it trades away an audit trail of *which* donors were shown for *which* request, in exchange for simplicity.

`ADMINS` has no foreign-key relationship to any other table — admin actions (toggle eligibility, record donation, delete donor) modify `DONORS`/`DONATIONS` directly rather than being logged against the admin's own record.

---

## Figure B — Use Case Diagram

```mermaid
graph TB
    Donor((Donor))
    Hospital((Hospital))
    Admin((Admin))

    subgraph LifeLink["LifeLink System"]
        UC1[Register / Log in]
        UC2[Manage Profile]
        UC3[View Donation History]
        UC4[Check Eligibility Status]
        UC5[Use Chatbot Assistant]
        UC6[Submit Blood Request]
        UC7[View AI-Matched Donors]
        UC8[Track Request Status]
        UC9[Manage Donor Records]
        UC10[Record Donation]
        UC11[View System Analytics]
        UC12[Monitor Blood Shortage / Demand]
    end

    Donor --> UC1
    Donor --> UC2
    Donor --> UC3
    Donor --> UC4
    Donor --> UC5

    Hospital --> UC1
    Hospital --> UC6
    Hospital --> UC7
    Hospital --> UC8

    Admin --> UC1
    Admin --> UC9
    Admin --> UC10
    Admin --> UC11
    Admin --> UC12

    UC6 -.->|triggers| UC7
    UC10 -.->|updates| UC3
```

**Note vs. the proposal's use-case diagram:** the proposal names "AI System" as a fourth actor. In strict UML terms this is debatable — an actor is normally an external entity that *initiates* interaction with the system, whereas the AI service here is a backend collaborator invoked *by* the system in response to donor/hospital actions (it never acts independently). The diagram above reflects this by showing the AI's involvement as a system-internal trigger (`UC6 -.-> UC7`) rather than a fourth actor. This distinction is worth a sentence of critical commentary in the report — it's a legitimate architectural point, not just a diagramming nitpick.

---

## Figure C — System Architecture Diagram

```mermaid
graph LR
    subgraph Client
        Browser[Browser<br/>Blade views]
    end

    subgraph Laravel["Laravel 12 Application"]
        Routes[Routes<br/>donor / hospital / admin guards]
        Controllers[Controllers]
        Models[Eloquent Models]
        AiService[AiEligibilityService]
    end

    subgraph DB["Database"]
        SQLDB[(SQLite / MySQL)]
    end

    subgraph AI["Python AI Service (FastAPI)"]
        Predict["/predict<br/>Logistic Regression"]
        Response["/predict-response<br/>Random Forest"]
        Anomaly["/detect-anomaly<br/>Isolation Forest"]
        Shortage["/predict-shortage<br/>k-NN"]
        Forecast["/forecast-demand<br/>Linear Regression"]
        Cluster["/cluster-donors<br/>K-Means"]
        Chat["/chatbot<br/>TF-IDF + k-NN"]
    end

    Browser -->|HTTP| Routes
    Routes --> Controllers
    Controllers --> Models
    Models --> SQLDB
    Controllers --> AiService
    AiService -->|HTTP, per-request| Predict
    AiService -->|HTTP, per-request| Response
    AiService -->|HTTP, per-request| Anomaly
    Controllers -->|HTTP, per-request| Shortage
    Controllers -->|HTTP, per-request| Forecast
    Controllers -->|HTTP, per-request| Cluster
    Controllers -->|HTTP, per-request| Chat
```

**Note:** this architecture diagram makes explicit something the report's Analysis chapter should discuss critically — of the seven AI endpoints, only `/predict` loads a model that was trained offline and persisted to disk (see the AI Model Evaluation Report). The other six either train in-memory at process start or retrain from scratch on every single request. This is an architectural weakness worth surfacing visually as well as in prose: the diagram treats all seven endpoints uniformly, but their underlying reliability is not uniform at all.
