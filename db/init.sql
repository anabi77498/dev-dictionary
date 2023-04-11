--- Resources ---
CREATE TABLE resources (
  id INTEGER NOT NULL UNIQUE,
  name TEXT NOT NULL,
  subject TEXT NOT NULL,
  url TEXT NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT)
);

--- Reviews (real) ---
CREATE TABLE reviews (
  id INTEGER NOT NULL UNIQUE,
  rating_mean INTEGER NOT NULL,
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
  resource_id INTEGER NOT NULL UNIQUE,
  review_id INTEGER NOT NULL UNIQUE,
  PRIMARY KEY(id AUTOINCREMENT) FOREIGN KEY (resource_id) REFERENCES resources(id) FOREIGN KEY(review_id) REFERENCES reviews(id)
);

--- Tags ---
CREATE TABLE tags (
  id INTEGER NOT NULL UNIQUE,
  name INTEGER UNIQUE NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT)
);

-- Tech and Tags entries --
CREATE TABLE tech_tags (
  id INTEGER NOT NULL UNIQUE,
  tech_id INTEGER NOT NULL,
  tag_id INTEGER NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT) FOREIGN KEY (tech_id) REFERENCES techs(id) FOREIGN KEY (tag_id) REFERENCES tags(id)
);

-- Resources Seed Data --
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
    'https://www.example.com/junit'
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
  (3, 10, 5, 20);

INSERT INTO
  reviews (
    rating_mean,
    rating_count,
    hot_yes_count,
    hot_count
  )
VALUES
  (4, 10, 12, 22);

-- Tags Seed Data --
INSERT INTO
  tags (name)
VALUES
  (1);

INSERT INTO
  tags (name)
VALUES
  (5);

-- Techs Seed Data --
INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    resource_id,
    review_id
  )
VALUES
  (
    'OCaml',
    'A high-level programming language',
    'let x = 4 :: 2 :: 3 :: []',
    'OCaml is a powerful and expressive programming language for functional programming',
    1,
    1
  );

INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    resource_id,
    review_id
  )
VALUES
  (
    'JUnit',
    'A unit testing framework for Java',
    '@Test
      public void testAdd() {
        Calculator calculator = new Calculator(); ||
        int result = calculator.add(2, 3); ||
        assertEquals(5, result);
    }',
    'JUnit is a popular open-source unit testing framework for Java programming language.',
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
  (2, 5);
