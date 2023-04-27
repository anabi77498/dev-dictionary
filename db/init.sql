--- Resources ---
CREATE TABLE resources (
  id INTEGER NOT NULL UNIQUE,
  name TEXT NOT NULL,
  subject TEXT NOT NULL,
  url TEXT NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT)
);

--- Reviews ---
CREATE TABLE reviews (
  id INTEGER NOT NULL UNIQUE,
  rating_mean REAL NOT NULL,
  rating_count INTEGER NOT NULL,
  hot_yes_count INTEGER NOT NULL,
  hot_count INTEGER NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT)
);

--- Techs/Technologies ---
CREATE TABLE techs (
  id INTEGER NOT NULL UNIQUE,
  name TEXT NOT NULL UNIQUE,
  definition TEXT NOT NULL UNIQUE,
  example TEXT,
  description TEXT NOT NULL UNIQUE,
  file_ext TEXT NOT NULL,
  file_source TEXT,
  resource_id INTEGER NOT NULL UNIQUE,
  review_id INTEGER NOT NULL UNIQUE,
  PRIMARY KEY(id AUTOINCREMENT) FOREIGN KEY (resource_id) REFERENCES resources(id) FOREIGN KEY(review_id) REFERENCES reviews(id)
);

--- Tags ---
CREATE TABLE tags (
  id INTEGER NOT NULL UNIQUE,
  name TEXT UNIQUE NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT)
);

-- Tech and Tags entries --
CREATE TABLE tech_tags (
  id INTEGER NOT NULL UNIQUE,
  tech_id INTEGER NOT NULL,
  tag_id INTEGER NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT) FOREIGN KEY (tech_id) REFERENCES techs(id) FOREIGN KEY (tag_id) REFERENCES tags(id)
);

--- Users ---
CREATE TABLE users (
  id INTEGER NOT NULL UNIQUE,
  name TEXT NOT NULL,
  email TEXT,
  username TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT)
);

--- Sessions ---
CREATE TABLE sessions (
  id INTEGER NOT NULL UNIQUE,
  user_id INTEGER NOT NULL,
  session TEXT NOT NULL UNIQUE,
  last_login TEXT NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT) FOREIGN KEY(user_id) REFERENCES users(id)
);

INSERT INTO
  resources (name, subject, url)
VALUES
  (
    'FreeCodeCamp',
    'OCaml',
    'https://www.freecodecamp.org/'
  );

INSERT INTO
  resources (name, subject, url)
VALUES
  (
    'TestResources',
    'JUnit',
    'https://www.tutorialspoint.com/junit/junit_test_framework.htm'
  );

-- Reviews Seed Data --
INSERT INTO
  reviews (
    rating_mean,
    rating_count,
    hot_yes_count,
    hot_count
  )
VALUES
  (4.8, 10, 16, 20);

INSERT INTO
  reviews (
    rating_mean,
    rating_count,
    hot_yes_count,
    hot_count
  )
VALUES
  (4.0, 10, 20, 22);

-- Tags Seed Data --
INSERT INTO
  tags (name)
VALUES
  ('Programming Language');

INSERT INTO
  tags (name)
VALUES
  ('Web Development');

INSERT INTO
  tags (name)
VALUES
  ('App Development');

INSERT INTO
  tags (name)
VALUES
  ('Data Science, ML and AI');

INSERT INTO
  tags (name)
VALUES
  ('Game Development');

INSERT INTO
  tags (name)
VALUES
  ('Operating Systems');

INSERT INTO
  tags (name)
VALUES
  ('Software Testing');

INSERT INTO
  tags (name)
VALUES
  ('Development Tools');

-- Techs Seed Data --
INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    file_ext,
    file_source,
    resource_id,
    review_id
  )
VALUES
  (
    'OCaml',
    'A high-level programming language that emphasizes strong static typing, type inference, and pattern matching',
    'let x = 4 :: 2 :: 3 :: [] in
    match x with
    | [] -> [891]
    | h :: t -> t
    | _ -> faiwith "Not a List!!"',
    'OCaml is a functional programming language that is used for a variety of applications, including scientific computing, symbolic computation, and systems programming. It is designed to be expressive, concise, and safe, with a strong type system that helps catch errors at compile time. OCaml also features type inference, garbage collection, and support for imperative programming, making it a versatile language that can handle a wide range of programming paradigms. One of the key features of OCaml is its use of pattern matching, which allows developers to write concise and expressive code for data manipulation and processing. OCaml is often used in the development of compilers, interpreters, and other tools for programming languages, due to its strong typing, efficient memory management, and support for abstract data types. Additionally, OCaml is commonly used in the development of web applications, database systems, and financial software. Its popularity has also been driven by the fact that it is an open-source language with an active community of developers who contribute libraries, tools, and frameworks. Overall, OCaml is a powerful and flexible programming language that can be used in a variety of contexts and applications.',
    "png",
    "https://icon-icons.com/icon/file-type-ocaml/130288",
    1,
    1
  );

INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    file_ext,
    file_source,
    resource_id,
    review_id
  )
VALUES
  (
    'JUnit',
    'JUnit is a unit testing framework for Java that provides a simple way to write and run repeatable automated tests to ensure the code behaves as expected.',
    '@Test
      public void testAdd() {
        Calculator calculator = new Calculator();
        int result = calculator.add(2, 3);
        assertEquals(5, result);
    }',
    'JUnit is an open-source testing framework for the Java programming language, designed to help developers write and run repeatable tests. It provides a set of annotations and assertion methods that allow developers to test their code in a structured and organized manner. Developers can use JUnit to create a suite of tests to ensure their code works as expected, and to identify and fix issues quickly. JUnit allows developers to automate their testing process, which is particularly useful when dealing with large codebases or when making changes to existing code. JUnit tests are written in Java and can be integrated with most popular development environments, such as Eclipse or IntelliJ IDEA. Developers can write tests for individual units of code (unit testing), or for entire applications (integration testing). Test results are displayed in a user-friendly format, indicating which tests passed and which failed. JUnit is widely used in the industry and is considered a standard for unit testing in Java. Its popularity is due to its simplicity, ease of use, and integration with popular development environments. JUnit has become a crucial tool for ensuring software quality and minimizing the risk of bugs and errors in Java applications.',
    "png",
    "https://www.opsera.io/platform/unified-insights",
    2,
    2
  );

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (1, 1);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (2, 7);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (2, 8);

-- password: monkey
INSERT INTO
  users (id, name, email, username, password)
VALUES
  (
    1,
    'Asad Nabi',
    'an448@cornell.edu',
    'asad',
    '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.' -- monkey
  );
