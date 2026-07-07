<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\FaqCategory;
use App\Models\FaqArticle;
use App\Models\TeamMember;
use App\Models\Event;

class PhaseOneSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedFaqCategories();
        $this->seedTeamMembers();
        $this->seedEvents();
    }

    private function seedFaqCategories(): void
    {
        $categories = [
            [
                'name'        => 'Police & Your Rights',
                'icon'        => '🚔',
                'description' => 'Know your rights when dealing with law enforcement officers.',
                'articles'    => [
                    [
                        'title'   => 'What are my rights when the police stop me?',
                        'excerpt' => 'A guide to your constitutional rights when stopped by Nigerian police officers on the road or in public.',
                        'content' => '<h2>Your Rights During a Police Stop</h2>
<p>Under the Nigerian Constitution and the Administration of Criminal Justice Act (ACJA) 2015, you have specific rights when stopped by police officers. Understanding these rights can protect you from unlawful treatment.</p>

<h3>1. Right to Know Why You Are Being Stopped</h3>
<p>You have the right to ask the officer why you are being stopped. A police officer must have a lawful reason for detaining you. Simply being in a particular area or looking "suspicious" is not sufficient grounds for arrest.</p>

<h3>2. Right to Remain Silent</h3>
<p>You are not obligated to answer questions beyond providing your basic identity. Section 35(2) of the 1999 Constitution (as amended) protects you from self-incrimination. You can politely say: "I would like to remain silent until I speak with a lawyer."</p>

<h3>3. Right Not to Pay Money on the Road</h3>
<p>This is very important: <strong>you are not required to pay any money to police officers on the road</strong>. Extortion by police officers is a criminal offence. If an officer demands money, note their badge number and report to the IPOB or the nearest complaints authority.</p>

<h3>4. Right Against Assault and Intimidation</h3>
<p>No police officer has the right to beat, slap, or physically assault you. Section 34 of the Constitution guarantees the dignity of the human person. Police brutality can be reported to:</p>
<ul>
<li>The IPOB (Independent Police Oversight Board)</li>
<li>The Human Rights Commission</li>
<li>ESUT Law Clinic for guidance on next steps</li>
</ul>

<h3>5. Right to Record the Encounter</h3>
<p>You may record an encounter with a police officer as long as you are not obstructing police duties. Keep your phone visible and do not be aggressive about it.</p>

<h3>6. If You Are Arrested</h3>
<p>If you are arrested, you must be informed of the reason in a language you understand. You have the right to contact a lawyer and a family member. You must be brought before a court within 24 hours (48 hours if the court is unavailable on weekends/public holidays) under the ACJA.</p>',
                    ],
                    [
                        'title'   => 'Can the police search my property without a warrant?',
                        'excerpt' => 'When police can and cannot search your home, car, or personal belongings under Nigerian law.',
                        'content' => '<h2>Search and Seizure in Nigeria</h2>
<p>Section 37 of the 1999 Constitution guarantees the right to privacy of your property. However, there are lawful exceptions to when police can conduct searches without a warrant.</p>

<h3>When a Warrant Is Required</h3>
<p>Generally, police must obtain a search warrant from a magistrate or judge before searching your home or private property. The warrant must specify the premises to be searched and items to be seized.</p>

<h3>Exceptions — When Police Can Search Without a Warrant</h3>
<ul>
<li><strong>Incident to lawful arrest:</strong> If you have been lawfully arrested, police may search your person and the immediate area.</li>
<li><strong>Exigent circumstances:</strong> If police believe evidence may be destroyed, they can act without a warrant.</li>
<li><strong>Your consent:</strong> If you voluntarily consent to the search. Note: you have the right to refuse.</li>
<li><strong>Stop and search:</strong> Under certain security situations (though this power is frequently abused).</li>
</ul>

<h3>What To Do If You Believe a Search Was Unlawful</h3>
<p>Document everything — what was searched, what was taken, the names or badge numbers of officers, and witnesses present. Any evidence obtained from an unlawful search may be inadmissible in court. Submit an enquiry to our clinic for specific guidance on your situation.</p>',
                    ],
                ],
            ],
            [
                'name'        => 'Student Rights',
                'icon'        => '🎓',
                'description' => 'Rights of students in disciplinary processes, academics, and campus life.',
                'articles'    => [
                    [
                        'title'   => 'What are my rights during school disciplinary proceedings?',
                        'excerpt' => 'Understanding procedural fairness and your right to a hearing in university disciplinary matters.',
                        'content' => '<h2>Student Rights in Disciplinary Proceedings</h2>
<p>As a student, you are entitled to a fair process before any disciplinary action is taken against you. The principles of natural justice require that:</p>

<h3>The Right to Be Heard (Audi Alteram Partem)</h3>
<p>You must be given an adequate opportunity to present your side of the story before any penalty is imposed. A student should never be punished based on one-sided proceedings or without an opportunity to respond to allegations.</p>

<h3>The Right to Know the Charges Against You</h3>
<p>You must be clearly informed of what you are being accused of — in writing where possible. Vague or unclear allegations are procedurally defective.</p>

<h3>Right Against Secret Trials</h3>
<p>Disciplinary hearings must be conducted with transparency. You are entitled to know who is judging your case, though the specific format may vary by institution.</p>

<h3>Right to Appeal</h3>
<p>Every disciplinary decision carries a right of appeal. This is usually to a higher body within the institution. If that fails, you may seek judicial review in court.</p>

<h3>Academic Integrity Matters</h3>
<p>If accused of exam malpractice or academic dishonesty, do not sign any admission statement under duress. Seek legal guidance immediately.</p>',
                    ],
                    [
                        'title'   => 'Can a lecturer seize my phone, assault me, or extort me?',
                        'excerpt' => 'The law on lecturer-student conduct and what to do if a lecturer violates your rights.',
                        'content' => '<h2>Lecturer Conduct and Student Rights</h2>

<h3>Phone Seizure</h3>
<p>A lecturer does not have the legal authority to permanently confiscate your personal property. A lecturer may ask you to put a device away or temporarily hold it during an examination, but permanent seizure of your property without due process is unlawful.</p>

<h3>Assault</h3>
<p><strong>Physical assault is a crime under Nigerian criminal law, regardless of who commits it.</strong> A lecturer who slaps, beats, or physically harms a student has committed criminal assault and is also in violation of the institution\'s code of conduct. Such matters should be reported to:</p>
<ul>
<li>The institution\'s student welfare office</li>
<li>The nearest police station</li>
<li>The institution\'s governing council or senate</li>
<li>ESUT Law Clinic for guidance</li>
</ul>

<h3>Extortion (Sex for Marks / Money for Grades)</h3>
<p>Demanding sexual favours, money, or gifts in exchange for academic marks or grades is illegal. It may constitute criminal extortion, sexual harassment, and an abuse of authority. Evidence (messages, recordings where lawfully obtained) should be preserved. Complaints can be made confidentially — contact our clinic for guidance on the process.</p>',
                    ],
                ],
            ],
            [
                'name'        => 'Sexual Harassment',
                'icon'        => '🛡️',
                'description' => 'Understanding sexual harassment, what constitutes it, and how to report it.',
                'articles'    => [
                    [
                        'title'   => 'What counts as sexual harassment under Nigerian law?',
                        'excerpt' => 'A clear definition of sexual harassment and the legal framework protecting individuals in Nigeria.',
                        'content' => '<h2>Sexual Harassment in Nigeria</h2>

<h3>Definition</h3>
<p>Sexual harassment is any unwanted conduct of a sexual nature. It includes physical, verbal, and non-verbal behaviour. The key element is that the conduct is <strong>unwanted</strong> — consent is absent or has been withdrawn.</p>

<h3>Examples of Sexual Harassment</h3>
<ul>
<li>Unwanted touching, grabbing, or physical contact of a sexual nature</li>
<li>Requesting sexual favours in exchange for grades, employment, or benefits</li>
<li>Sexual comments, jokes, or innuendo that create a hostile environment</li>
<li>Sending sexual messages, images, or materials</li>
<li>Persistent unwanted romantic or sexual attention after clear refusal</li>
<li>Exposure of private body parts</li>
<li>Voyeurism or non-consensual recording of intimate acts</li>
</ul>

<h3>Legal Framework</h3>
<p>The Violence Against Persons (Prohibition) Act, 2015 (VAPP Act) provides comprehensive protection against sexual harassment and related offences. Enugu State has also domesticated the VAPP Act. Additional protections exist under the Criminal Code and Penal Code.</p>

<h3>How to Report</h3>
<p>You may report to: the institution\'s gender desk or harassment unit, the university administration, or the police. <strong>You do not have to confront the harasser alone.</strong> Our clinic can provide confidential guidance and help you understand the reporting process.</p>',
                    ],
                ],
            ],
            [
                'name'        => 'Employment & Labour',
                'icon'        => '💼',
                'description' => 'Rights of workers, employees, and individuals in employment situations.',
                'articles'    => [
                    [
                        'title'   => 'What are my rights as an employee in Nigeria?',
                        'excerpt' => 'Core employee rights under the Labour Act and other employment laws in Nigeria.',
                        'content' => '<h2>Employee Rights in Nigeria</h2>
<p>The Labour Act (Cap L1, LFN 2004) is the primary law governing employment in Nigeria. Key rights include:</p>

<h3>Right to Written Terms of Employment</h3>
<p>An employer must provide you with a written statement of your employment terms within three months of commencement. This should include your salary, hours of work, leave entitlement, and notice period.</p>

<h3>Right to Minimum Wage</h3>
<p>The National Minimum Wage Act sets a floor on wages. As of the latest amendment, the national minimum wage is ₦70,000 per month. No employer may pay below this amount.</p>

<h3>Right to Leave</h3>
<p>Employees are entitled to at least 6 working days of annual leave after 12 months of continuous service. Some categories of workers (e.g. underground workers) are entitled to more.</p>

<h3>Protection Against Wrongful Dismissal</h3>
<p>You cannot be dismissed without proper notice or just cause. An employer must follow due process and may not dismiss an employee in a manner that violates the contract or breaches natural justice principles.</p>

<h3>Right Against Discrimination</h3>
<p>Discrimination in employment on grounds of sex, religion, ethnicity, or disability is unlawful. The Constitution\'s equality provisions and various labour laws protect against such treatment.</p>',
                    ],
                ],
            ],
            [
                'name'        => 'Family Law',
                'icon'        => '👨‍👩‍👧',
                'description' => 'Legal aspects of marriage, divorce, custody, and family relationships.',
                'articles'    => [
                    [
                        'title'   => 'What is the legal age of marriage in Nigeria?',
                        'excerpt' => 'Understanding the legal frameworks governing marriage age in Nigeria, including federal and state laws.',
                        'content' => '<h2>Legal Age of Marriage in Nigeria</h2>

<h3>Federal Position</h3>
<p>The Child Rights Act 2003 sets the minimum age of marriage at <strong>18 years</strong> for both males and females throughout Nigeria. A marriage contracted with a child below 18 is void and those facilitating it may face criminal liability.</p>

<h3>State Domestication</h3>
<p>The Child Rights Act requires individual state domestication to be fully effective. As of now, most Southern states including Enugu State have domesticated the Act. In states that have not, the position may differ, though the federal minimum standard should guide best practice.</p>

<h3>Marriage Under the Marriage Act</h3>
<p>For statutory marriages (conducted under the Marriage Act), persons under 21 require parental consent. The minimum age under this Act is 18.</p>

<h3>Customary and Islamic Law Marriages</h3>
<p>While customary and Islamic law marriages may in practice occur at younger ages, they are subject to the overarching provisions of the Child Rights Act in states that have domesticated it. Courts have increasingly upheld the protection of children against underage marriage.</p>',
                    ],
                ],
            ],
            [
                'name'        => 'General Legal Enquiry',
                'icon'        => '⚖️',
                'description' => 'General legal questions that span multiple areas of law.',
                'articles'    => [
                    [
                        'title'   => 'What should I do if I am wrongly accused of a crime?',
                        'excerpt' => 'Step-by-step guidance on protecting yourself if you are falsely accused of a criminal offence.',
                        'content' => '<h2>If You Are Wrongly Accused of a Crime</h2>

<h3>Stay Calm</h3>
<p>Do not panic or act aggressively. An emotional or aggressive response can be used against you and may complicate your situation.</p>

<h3>Do Not Sign Any Statement Without Legal Advice</h3>
<p>Police may ask you to sign a statement. <strong>Do not sign anything without first reading it carefully and ideally without consulting a lawyer.</strong> You have the right to request legal representation before making any statement.</p>

<h3>Assert Your Right to Silence</h3>
<p>Section 35(2) of the Constitution protects you from being compelled to give evidence against yourself. You may clearly state: "I wish to exercise my right to remain silent until I have consulted with a lawyer."</p>

<h3>Request to Contact Someone</h3>
<p>You have the right to inform a family member, friend, or lawyer of your situation. Police are required to facilitate this.</p>

<h3>Presume Innocence</h3>
<p>Section 36(5) of the Constitution provides that every person charged with a criminal offence shall be <strong>presumed innocent until proven guilty beyond reasonable doubt.</strong> The burden of proof lies with the prosecution, not you.</p>

<h3>Document Everything</h3>
<p>Note the names and badge numbers of officers, dates, times, witnesses, and any injuries. This information may be critical later.</p>

<h3>Seek Legal Help</h3>
<p>Contact the ESUT Law Clinic or a qualified legal practitioner as early as possible. Early legal intervention can significantly affect outcomes in criminal matters.</p>',
                    ],
                    [
                        'title'   => 'How do I write a petition to the police?',
                        'excerpt' => 'A step-by-step guide to drafting a formal petition or complaint letter to Nigerian police.',
                        'content' => '<h2>Writing a Petition to the Police</h2>
<p>A petition to the police is a formal written complaint asking them to investigate an allegation or grievance. Here is what a good petition should include:</p>

<h3>Required Elements</h3>
<ul>
<li><strong>Your name and address</strong> — clearly state who you are and where you can be reached</li>
<li><strong>Date and address it to the appropriate officer</strong> — e.g., The Divisional Police Officer, [Station Name] Division</li>
<li><strong>Who you are complaining against</strong> — name, address, or description of the subject of the complaint</li>
<li><strong>A clear account of what happened</strong> — dates, times, locations, and what was done or said</li>
<li><strong>Names of witnesses</strong> — if any were present</li>
<li><strong>What you want the police to do</strong> — arrest, investigate, etc.</li>
<li><strong>Your signature</strong></li>
</ul>

<h3>Sample Opening</h3>
<p>"I, [Your Full Name], residing at [Your Address], hereby petition the above-named station regarding the following matter..."</p>

<h3>Tips</h3>
<ul>
<li>Be factual and concise — avoid emotional language</li>
<li>Attach supporting documents where available</li>
<li>Make a copy before submitting and get a stamp/acknowledgement</li>
<li>Follow up if no action is taken within a reasonable time</li>
</ul>

<p>If you need help drafting a specific petition, <a href="/get-legal-help">submit a free enquiry</a> to our clinic.</p>',
                    ],
                ],
            ],
        ];

        foreach ($categories as $idx => $cat) {
            $category = FaqCategory::create([
                'name'        => $cat['name'],
                'slug'        => Str::slug($cat['name']),
                'description' => $cat['description'],
                'icon'        => $cat['icon'],
                'sort_order'  => $idx,
                'is_active'   => true,
            ]);

            foreach ($cat['articles'] as $art) {
                FaqArticle::create([
                    'faq_category_id' => $category->id,
                    'title'           => $art['title'],
                    'slug'            => Str::slug($art['title']),
                    'content'         => $art['content'],
                    'excerpt'         => $art['excerpt'],
                    'is_published'    => true,
                    'helpful_yes'     => rand(5, 30),
                    'helpful_no'      => rand(0, 5),
                    'views'           => rand(20, 200),
                ]);
            }
        }
    }

    private function seedTeamMembers(): void
    {
        $lecturers = [
            [
                'name' => 'Rev. Sr. Dr. Maria Chigozie Onuegbulam',
                'role' => 'Dean, Faculty of Law / Innovation and Supervising Lecturer',
                'type' => 'lecturer',
                'photo_path' => 'team/maria-onuegbulam.jpg',
            ],
            [
                'name' => 'Prof. Osita Nnamani Ogbu',
                'role' => 'Supervising Lecturer',
                'type' => 'lecturer',
                'photo_path' => 'team/osita-ogbu.jpg',
            ],
            [
                'name' => 'Dr. Collins Chijioke Ani',
                'role' => 'Innovative Research / Supervising Lecturer',
                'type' => 'lecturer',
                'photo_path' => 'team/collins-ani.jpg',
            ],
            [
                'name' => 'Dr. Chijioke Agbo',
                'role' => 'Law Clinic Coordinator / Supervising Lecturer',
                'type' => 'lecturer',
                'photo_path' => 'team/chijioke-agbo.jpg',
            ],
        ];

        $students = [
            [
                'name' => 'Ahanonu Uchechukwu Charles',
                'role' => 'Student Adviser',
                'level' => null,
                'type' => 'student',
                'photo_path' => 'team/charles-ahanonu.jpg',
            ],
            [
                'name' => 'Nwodo Immanuel Obinna',
                'role' => 'Student Adviser',
                'level' => null,
                'type' => 'student',
                'photo_path' => 'team/immanuel-nwodo.jpg',
            ],
            [
                'name' => 'Omenyi Kingsley Ifeanyichukwu',
                'role' => 'Student Adviser',
                'level' => null,
                'type' => 'student',
                'photo_path' => 'team/kingsley-omenyi.jpg',
            ],
            [
                'name' => 'Nkemjika Chikamso',
                'role' => 'Student Adviser',
                'level' => null,
                'type' => 'student',
                'photo_path' => 'team/chikamso-okonkwo.jpg',
            ],
            [
                'name' => 'Bethel Chimzurumoke Bright',
                'role' => 'Student Adviser',
                'level' => null,
                'type' => 'student',
                'photo_path' => 'team/bethel-bright.jpg',
            ],
            [
                'name' => 'Ugwoke Precious',
                'role' => 'Student Adviser',
                'level' => null,
                'type' => 'student',
                'photo_path' => null, // no image found
            ],
            [
                'name' => 'Mbah A. Favour',
                'role' => 'Student Adviser',
                'level' => null,
                'type' => 'student',
                'photo_path' => 'team/favour-mbah.jpg',
            ],
        ];

        foreach (array_merge($lecturers, $students) as $idx => $member) {
            TeamMember::create([
                ...$member,
                'sort_order' => $idx,
                'is_active' => true,
            ]);
        }
    }

    private function seedEvents(): void
    {
        Event::create([
            'title'                 => 'Know Your Rights: A Free Legal Awareness Seminar',
            'slug'                  => 'know-your-rights-legal-awareness-seminar-2025',
            'description'           => '<p>Join the ESUT Law Clinic for a free legal awareness seminar open to all ESUT students, staff, and members of the public.</p><p>Topics to be covered include: Police encounters and your constitutional rights, Sexual harassment laws and reporting channels, Student rights in academic and disciplinary settings, and How to access free legal help.</p><p>The session will be interactive — bring your questions!</p>',
            'location'              => 'ESUT Faculty of Law Moot Court, Agbani',
            'event_date'            => now()->addDays(14)->setTime(10, 0),
            'event_end_date'        => now()->addDays(14)->setTime(13, 0),
            'requires_registration' => true,
            'max_attendees'         => 200,
            'is_published'          => true,
        ]);
    }
}
