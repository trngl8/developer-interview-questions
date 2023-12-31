CREATE SEQUENCE questions_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE questions (
    id INTEGER PRIMARY KEY NOT NULL,
    title VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE SEQUENCE answers_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE answers(
    id INTEGER PRIMARY KEY NOT NULL,
    question_id INTEGER NOT NULL,
    body VARCHAR(255) NOT NULL,
    rate INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
