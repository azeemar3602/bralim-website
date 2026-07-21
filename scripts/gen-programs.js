/* Generates the 6 program detail pages into src/pages/ from structured data. */
const fs = require('fs');
const path = require('path');
const OUT = path.join(__dirname, '..', 'src', 'pages');

const programs = [
  {
    slug: 'program-community-support', icon: '🫶', ph: '',
    title: 'Community Support',
    intro: 'When you arrive in a new country, the difference between struggling and settling is often a community that has your back. Our Community Support programme welcomes new arrivals and stands with members through the milestones and challenges of life in the Netherlands.',
    objectives: ['Help new arrivals settle and feel at home', 'Offer guidance on practical everyday matters', 'Provide a support network in times of need', 'Foster friendships that reduce isolation'],
    activities: ['Welcome sessions and buddy pairing for newcomers', 'Practical information on life in the Netherlands', 'Peer support and check-ins for members', 'Community response during difficult times'],
    participate: 'Reach out to us to join, request support, or volunteer as a community buddy. Every member is welcome — whether you need support today or want to offer it to others.',
  },
  {
    slug: 'program-culture-heritage', icon: '🥁', ph: 'ph--gold',
    title: 'Culture & Heritage',
    intro: 'Our culture is our compass. This programme keeps Ugandan heritage, language, music, food and traditions alive for our members and shares them proudly with the wider Dutch society.',
    objectives: ['Celebrate Ugandan culture and traditions', 'Pass heritage and language to the next generation', 'Share our culture with the wider community', 'Create moments of joy and belonging'],
    activities: ['Cultural celebrations and national day events', 'Music, dance and traditional performances', 'Food festivals and community meals', 'Language and heritage sessions for children'],
    participate: 'Join our cultural events, perform, cook, or help us organise. If you carry a tradition worth sharing, we want to celebrate it with you.',
  },
  {
    slug: 'program-youth-education', icon: '🎓', ph: '',
    title: 'Youth & Education',
    intro: 'Our young people are the future of the community. This programme provides mentoring, tutoring and opportunities that help youth flourish academically, socially and personally.',
    objectives: ['Support academic success for students', 'Provide mentorship and positive role models', 'Build confidence, identity and leadership', 'Open doors to opportunities and networks'],
    activities: ['Mentorship pairing with community professionals', 'Homework help and tutoring support', 'Youth workshops and leadership sessions', 'Careers guidance and skills days'],
    participate: 'Enrol a young person, volunteer as a mentor or tutor, or partner with us on youth initiatives. Together we can help every young person reach their potential.',
  },
  {
    slug: 'program-family-social', icon: '👨‍👩‍👧‍👦', ph: 'ph--sand',
    title: 'Family & Social Activities',
    intro: 'Community grows around shared moments. This programme brings families together through social gatherings and activities that build friendships and lasting memories.',
    objectives: ['Bring families together in a relaxed setting', 'Build friendships across the community', 'Create safe, fun spaces for all ages', 'Strengthen the bonds that make community'],
    activities: ['Family days, picnics and social gatherings', 'Sports, games and outdoor activities', 'Seasonal and festive celebrations', 'Informal meet-ups and community dinners'],
    participate: 'Come along with your family, suggest an activity, or help us host. All ages and backgrounds are welcome.',
  },
  {
    slug: 'program-professional-network', icon: '💼', ph: '',
    title: 'Professional & Business Network',
    intro: 'From first jobs to growing businesses, our members achieve more together. This programme connects professionals and entrepreneurs for networking, mentorship and mutual support.',
    objectives: ['Connect professionals across sectors', 'Support entrepreneurs and business owners', 'Share knowledge, skills and opportunities', 'Build a network that opens doors'],
    activities: ['Networking events and meet-ups', 'Business and career mentorship', 'Skills-sharing workshops and talks', 'Job and opportunity sharing'],
    participate: 'Join the network, offer mentorship, speak at an event, or partner with us. Whether you are starting out or established, there is a seat at the table.',
  },
  {
    slug: 'program-charity-outreach', icon: '🌍', ph: 'ph--gold',
    title: 'Charity & Outreach',
    intro: 'Giving back is part of who we are. This programme channels the generosity of our community into charitable action that supports people here in the Netherlands and back home in Uganda.',
    objectives: ['Support communities in need here and in Uganda', 'Coordinate fundraising and charitable drives', 'Respond to appeals with compassion', 'Manage resources transparently and ethically'],
    activities: ['Fundraising events and charity drives', 'Community outreach and support projects', 'Partnerships with charitable organisations', 'Donation campaigns for causes that matter'],
    participate: 'Donate, fundraise, volunteer, or suggest a cause. Every contribution — large or small — makes a real difference.',
  },
];

const li = (arr) => arr.map(x => `        <li>${x}</li>`).join('\n');

for (const p of programs) {
  const html = `---
slug: ${p.slug}
title: ${p.title}
description: ${p.intro.slice(0, 155).replace(/\n/g, ' ')}
---
<section class="page-hero">
  <div class="hero-bg"></div>
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="/">Home</a> <span>/</span> <a href="/programs.html">Programs</a> <span>/</span> <span>${p.title}</span></nav>
    <h1>${p.icon} ${p.title}</h1>
  </div>
</section>

<section class="section">
  <div class="container split">
    <div class="prose" data-reveal>
      <span class="eyebrow">Introduction</span>
      <h2>About this programme</h2>
      <p class="lead">${p.intro}</p>
    </div>
    <div data-reveal>
      <div class="ph ph--4x3 ${p.ph}"><span class="ph-label">Photo: ${p.title}</span></div>
    </div>
  </div>
</section>

<section class="section section--cream">
  <div class="container split">
    <div class="prose" data-reveal>
      <span class="eyebrow">Objectives</span>
      <h2>What we aim to achieve</h2>
      <ul class="bullets">
${li(p.objectives)}
      </ul>
    </div>
    <div class="prose" data-reveal>
      <span class="eyebrow">Activities</span>
      <h2>What we do</h2>
      <ul class="bullets">
${li(p.activities)}
      </ul>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="grid grid-2">
      <div class="card" data-reveal style="border-top:5px solid var(--green-600)">
        <div class="card-icon">🙋</div>
        <h3>How to Participate</h3>
        <p>${p.participate}</p>
        <a href="/get-involved.html" class="btn btn-green" style="margin-top:14px">Get Involved</a>
      </div>
      <div class="card" data-reveal style="border-top:5px solid var(--gold-500)">
        <div class="card-icon">✉️</div>
        <h3>Programme Contact</h3>
        <p>Questions about ${p.title}? We're happy to help.</p>
        <ul class="footer-contact" style="margin-top:8px;color:var(--muted)">
          <li>Email: <a href="mailto:info@bralim.org">info@bralim.org</a></li>
          <li>Phone: +31 (0)00 000 0000 (placeholder)</li>
        </ul>
        <a href="/contact.html" class="btn btn-outline" style="margin-top:14px">Contact Us</a>
      </div>
    </div>
  </div>
</section>

<section class="section section--cream">
  <div class="container"><div class="cta-band" data-reveal>
    <h2>Take part in ${p.title}</h2>
    <p>Become a member or volunteer today and help this programme grow.</p>
    <div class="hero-cta"><a href="/membership.html" class="btn btn-gold btn-lg">Become a Member</a><a href="/programs.html" class="btn btn-light btn-lg">All Programs</a></div>
  </div></div>
</section>
`;
  fs.writeFileSync(path.join(OUT, p.slug + '.html'), html);
  console.log('wrote', p.slug + '.html');
}
