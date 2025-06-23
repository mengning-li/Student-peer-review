# 🗄️ Enhanced Database Structure Proposal

## Current vs Proposed Structure

### **Current Structure** (Simple)
```
Users → Enrollments → Courses → Assessments → Reviews
```

### **Proposed Structure** (Workshop + Group Based)
```
Users → Workshop_Enrollments → Workshops → Courses
     → Assessment_Participants → Assessments → Groups → Group_Members
                                           → Reviews (enhanced)
```

## 📊 Detailed Table Structure

### **Core Tables**

#### 1. `workshops`
```sql
id                  BIGINT PRIMARY KEY
course_id           BIGINT FOREIGN KEY → courses.id
name                VARCHAR(255)        -- "Workshop A", "Morning Group", etc.
description         TEXT
instructor_id       BIGINT FOREIGN KEY → users.id
max_capacity        INT
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

#### 2. `workshop_enrollments` 
```sql
id                  BIGINT PRIMARY KEY
user_id             BIGINT FOREIGN KEY → users.id
workshop_id         BIGINT FOREIGN KEY → workshops.id
course_id           BIGINT FOREIGN KEY → courses.id (for easier queries)
enrolled_at         TIMESTAMP
status              ENUM('active', 'dropped', 'completed')
created_at          TIMESTAMP
updated_at          TIMESTAMP

UNIQUE(user_id, course_id)  -- One workshop per course per student
```

#### 3. `assessment_participants`
```sql
id                  BIGINT PRIMARY KEY
user_id             BIGINT FOREIGN KEY → users.id
assessment_id       BIGINT FOREIGN KEY → assessments.id
workshop_id         BIGINT FOREIGN KEY → workshops.id
participation_type  ENUM('individual', 'group_leader', 'group_member')
joined_at           TIMESTAMP
status              ENUM('active', 'completed', 'withdrawn')
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

#### 4. `groups`
```sql
id                  BIGINT PRIMARY KEY
assessment_id       BIGINT FOREIGN KEY → assessments.id
name                VARCHAR(255)
description         TEXT
max_members         INT DEFAULT 6
created_by          BIGINT FOREIGN KEY → users.id
status              ENUM('forming', 'active', 'completed')
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

#### 5. `group_members`
```sql
id                  BIGINT PRIMARY KEY
group_id            BIGINT FOREIGN KEY → groups.id
user_id             BIGINT FOREIGN KEY → users.id
role                ENUM('leader', 'member', 'observer')
joined_at           TIMESTAMP
status              ENUM('active', 'left', 'removed')
created_at          TIMESTAMP
updated_at          TIMESTAMP

UNIQUE(group_id, user_id)
```

### **Enhanced Tables**

#### 6. `assessments` (Enhanced)
```sql
id                  BIGINT PRIMARY KEY
course_id           BIGINT FOREIGN KEY → courses.id
title               VARCHAR(255)
instruction         TEXT
max_score           INT
due_date            TIMESTAMP
type                ENUM('individual', 'group', 'cross_workshop')
review_type         ENUM('student_select', 'teacher_assign', 'group_internal')
required_reviews    INT
allow_cross_workshop BOOLEAN DEFAULT false
group_based         BOOLEAN DEFAULT false
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

#### 7. `reviews` (Enhanced)
```sql
id                  BIGINT PRIMARY KEY
assessment_id       BIGINT FOREIGN KEY → assessments.id
reviewer_id         BIGINT FOREIGN KEY → users.id
reviewee_id         BIGINT FOREIGN KEY → users.id (for individual)
group_id            BIGINT FOREIGN KEY → groups.id (for group reviews)
review_content      TEXT
score               INT
rating              INT (1-5)
feedback            TEXT
review_type         ENUM('individual', 'group', 'peer_to_peer', 'group_to_group')
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

## 🔗 Key Relationships

### **Workshop System**
- **One student** → **One workshop per course** (home workshop)
- **One workshop** → **Many students**
- **One instructor** → **Many workshops**

### **Assessment Participation**
- **Students can participate in assessments from other workshops**
- **Participation tracked separately from workshop enrollment**
- **Enables cross-workshop collaboration**

### **Group System**
- **Groups are assessment-specific**
- **Members can come from different workshops**
- **Supports both individual and group-based reviews**

## 🎯 Use Cases Enabled

### 1. **Workshop-Based Learning**
```sql
-- Get all students in a workshop
SELECT u.* FROM users u
JOIN workshop_enrollments we ON u.id = we.user_id
WHERE we.workshop_id = ? AND we.status = 'active'
```

### 2. **Cross-Workshop Assessment**
```sql
-- Students from Workshop A reviewing Workshop B's work
SELECT * FROM assessment_participants ap1
JOIN assessment_participants ap2 ON ap1.assessment_id = ap2.assessment_id
WHERE ap1.workshop_id != ap2.workshop_id
```

### 3. **Group Formation**
```sql
-- Create mixed groups from different workshops
SELECT g.name, u.name, w.name as workshop
FROM groups g
JOIN group_members gm ON g.id = gm.group_id
JOIN users u ON gm.user_id = u.id
JOIN workshop_enrollments we ON u.id = we.user_id
JOIN workshops w ON we.workshop_id = w.id
WHERE g.assessment_id = ?
```

### 4. **Complex Review Scenarios**
- **Individual reviews within workshop**
- **Group reviews across workshops**
- **Peer-to-peer reviews between specific students**
- **Group-to-group reviews**

## 🚀 Migration Strategy

### Phase 1: Add Workshop System
1. Create `workshops` table
2. Create `workshop_enrollments` table
3. Migrate existing enrollments to workshop enrollments

### Phase 2: Enhanced Assessment Participation
1. Create `assessment_participants` table
2. Migrate existing data
3. Update assessment logic

### Phase 3: Group System
1. Create `groups` and `group_members` tables
2. Enhance reviews table
3. Update review workflows

## 💡 Additional Considerations

### **Indexes for Performance**
```sql
-- Workshop enrollments
INDEX(user_id, course_id)
INDEX(workshop_id, status)

-- Assessment participants  
INDEX(user_id, assessment_id)
INDEX(assessment_id, workshop_id)

-- Groups
INDEX(assessment_id, status)

-- Group members
INDEX(group_id, status)
INDEX(user_id, group_id)

-- Enhanced reviews
INDEX(assessment_id, reviewer_id)
INDEX(group_id, review_type)
```

### **Business Rules**
1. **One workshop per course per student**
2. **Students can participate in multiple assessments**
3. **Groups are assessment-specific**
4. **Cross-workshop participation requires permission**
5. **Group size limits configurable per assessment**

### **Potential Extensions**
- **Workshop schedules and time slots**
- **Workshop-specific resources**
- **Group formation algorithms**
- **Workshop performance analytics**
- **Cross-workshop collaboration tracking**

## 🎉 Benefits of This Structure

1. **Scalability**: Supports large courses with multiple workshops
2. **Flexibility**: Enables various collaboration patterns
3. **Organization**: Clear structure for managing student cohorts
4. **Analytics**: Rich data for understanding learning patterns
5. **Future-proof**: Extensible for advanced features

This structure transforms your peer review system from a simple course-based model to a sophisticated workshop and group-based learning platform! 