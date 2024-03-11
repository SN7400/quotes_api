CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    category varchar(20) NOT NULL
);

INSERT INTO categories (category) VALUES
    ('War'),
    ('Life'),
    ('Death'),
    ('Mindfulness'),
    ('Silence'),
    ('Self-Discipline'),
    ('Truth'),
    ('Money'),
    ('Freedom');

CREATE TABLE authors (
    id SERIAL PRIMARY KEY,
    author varchar(50) NOT NULL
);

INSERT INTO authors (author) VALUES
    ('Siddhartha Gautama'),
    ('Marcus Aurelius'),
    ('Maya Angelou'),
    ('Eleanor Roosevelt'),
    ('Emily Dickinson');

CREATE TABLE quotes (
    id SERIAL PRIMARY KEY,
    quote text NOT NULL,
    author_id integer NOT NULL references authors(id),
    category_id integer NOT NULL references categories(id)
);

INSERT INTO quotes (quote, author_id, category_id) VALUES
    ('Do not dwell in the past, do not dream of the future, concentrate the mind on the present moment.', 1, 4),
    ('It is better to conquer yourself than to win a thousand battles. Then the victory is yours. It cannot be taken from you, not by angels or by demons, heaven or hell.', 1, 6),
    ('No one saves us but ourselves. No one can and no one may. We ourselves must walk the path.', 1, 6),
    ('We are what we think. All that we are arises with our thoughts. With our thoughts, we make the world.', 1, 4),
    ('Three things cannot be long hidden: the sun, the moon, and the truth.', 1, 7),
    ('When you arise in the morning, think of what a precious privilege it is to be alive - to breathe, to think, to enjoy, to love.', 2, 4),
    ('Very little is needed to make a happy life; it is all within yourself, in your way of thinking.', 2, 6),
    ('The only wealth which you will keep forever is the wealth you have given away.', 2, 8),
    ('Poverty is the mother of crime.', 2, 8),
    ('Confine yourself to the present.', 2, 4),
    ('When someone shows you who they are, believe them the first time.', 3, 7),
    ('The truth is, no one of us can be free until everybody is free.', 3, 9),
    ('We may encounter many defeats but we must not be defeated.', 3, 6),
    ('If you don''t like something, change it. If you can''t change it, change your attitude.', 3, 6),
    ('Nothing will work unless you do.', 3, 6),
    ('Great minds discuss ideas; average minds discuss events; small minds discuss people.', 4, 6),
    ('It is not fair to ask of others what you are not willing to do yourself.', 4, 6),
    ('Never allow a person to tell you no who doesn''t have the power to say yes.', 4, 6),
    ('I can not believe that war is the best solution. No one won the last war, and no one will win the next war.', 4, 1),
    ('It takes as much energy to wish as it does to plan.', 4, 6),
    ('Saying nothing... sometimes says the most.', 5, 5),
    ('Forever is composed of nows.', 5, 4),
    ('That it will never come again is what makes life sweet.', 5, 2),
    ('Dying is a wild night and a new road.', 5, 3),
    ('Where thou art, that is home.', 5, 4);